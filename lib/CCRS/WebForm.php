<?php
/**
 *
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\CCRS;

class WebForm
{
	/**
	 *
	 */
	function _create_ua()
	{
		$fcj = new \GuzzleHttp\Cookie\FileCookieJar(sprintf('%s/cookie.bin', $b2b_path), true);
		$gua = new \GuzzleHttp\Client(array(
			'base_uri' => 'https://lcb.wa.gov',
			'cookies' => $fcj,
			'headers' => array(
				'user-agent' => sprintf('OpenTHC/%s', APP_BUILD),
			),
			'http_errors' => false
		));
	}

	/**
	 *
	 */
	function get_captcha()
	{
		$b2b_path = Company::getPath('/b2b');

		$req_ulid = _ulid();
		$img_file = sprintf('%s/%s.jpeg', $b2b_path, $req_ulid);

		if (!is_file($img_file)) {

			$res = $gua->get('/manifest');
			$html = $res->getBody()->getContents();
			file_put_contents(sprintf('%s/%s-x1.html', $b2b_path, $req_ulid), $html);

			// get captcha
			if (!preg_match('/<img src="(\/image_captcha[^"]+)" width/', $html, $m)) {
				__exit_json([
					'data' => null,
					'meta' => [ 'detail' => 'Cannot Load Manifest Application from LCB [ABC-059]' ]
				], 403);
			}

			$img = $m[1];

			$res = $gua->get($img);
			$jpeg = $res->getBody()->getContents();
			file_put_contents($img_file, $jpeg);

			__exit_json([
				'data' => [
					'id' => $req_ulid,
					'jpeg' => base64_encode($jpeg)
				],
				'meta' => []
			]);
		}

	}

	/**
	 *
	 */
	function upload()
	{
		$data = _json_post_parse();
		$req_ulid = $data['req'];
		$img_text = strtolower($data['txt']);

		// Step 1 Submit Captcha
		$html = file_get_contents(sprintf('%s/%s-x1.html', $b2b_path, $req_ulid));
		$post = _get_form_data($html);
		if ('1' !== $post['details[page_num]']) {
			$msg = [ "Incorrect Page Num; Expect:1; Actual:{$post['details[page_num]']};" ];
			$msg[] = _extract_error($html);
			__exit_json([
				'data' => null,
				'meta' => [ 'detail' => implode('; ', $msg) ]
			], 500);
		}

		$post['captcha_response'] = strtolower($img_text);

		// Post Image, Get Step 2
		$res = $gua->post('/manifest', [ 'form_params' => $post ]);
		$html = $res->getBody()->getContents();
		$file = sprintf('%s/%s-x2.html', $b2b_path, $req_ulid);
		file_put_contents($file, $html);

		$post = _get_form_data($html);
		if ('2' !== $post['details[page_num]']) {
			$msg = [ "Incorrect Page Num; Expect:2; Actual:{$post['details[page_num]']};" ];
			$msg[] = _extract_error($html);
			__exit_json([
				'data' => null,
				'meta' => [ 'detail' => implode('; ', $msg) ]
			], 500);

		}

		// @todo Should take a POST of the properly inflated OpenTHC style JSON
		$B2B = $data['b2b'];
		// if (empty($B2B['id'])) {
		// 	__exit_json([
		// 		'data' => null,
		// 		'meta' => null
		// 	], 404);
		// }

		// Inflate to OpenTHC desired Model
		$B2B_sub = new Manifest($B2B['id']);
		$line_item_text = $B2B_sub->fold_items_to_lcb_webform();
		// $B2B = $B2B->inflateArray();

		// Dates
		// @bug Timezone not getting saved on create? /mbw 2022-146
		// $dt0 = new DateTime($B2B['depart_at'], new DateTimezone($_SESSION['Company']['tz']));
		// $dt1 = new DateTime($B2B['arrive_at'], new DateTimezone($_SESSION['Company']['tz']));
		$dt0 = new DateTime($B2B['depart_at']);
		$dt1 = new DateTime($B2B['arrive_at']);
		$dt0->setTimezone(new DateTimezone($_SESSION['tz']));
		$dt1->setTimezone(new DateTimezone($_SESSION['tz']));

		// Refactor Data
		switch ($B2B['shipping']['type']) {
			case 'outgoing/carrier':
				$post['submitted[transportation_type]'] = 3; // Carrier/Transporter
				break;
			case 'outgoing/delivery':
				$post['submitted[transportation_type]'] = 1; // Delivery
				break;
			case 'outgoing/pickup':
				$post['submitted[transportation_type]'] = 2; // Pick-Up
				break;
		}

		$post['submitted[scheduled_transportation_date][month]'] = $dt0->format('n');
		$post['submitted[scheduled_transportation_date][day]'] = $dt0->format('j');
		$post['submitted[scheduled_transportation_date][year]'] = $dt0->format('Y');// '2021';
		$post['submitted[licensee_license_number]'] = $_SESSION['Company']['guid'];
		$post['submitted[origin_license_number]'] = $B2B['source']['code'];
		$post['submitted[licensee_name]'] = $B2B['source']['name'];
		$post['submitted[licensee_address]'] = $B2B['source']['address_full'];
		$post['submitted[licensee_phone]'] = $B2B['source']['phone'];
		$post['submitted[licensee_email]'] = $B2B['source']['email'];

		// Post to Step3
		$res3 = $gua->post('/manifest', [ 'form_params' => $post ]);
		$html = $res3->getBody()->getContents();
		$file = sprintf('%s/%s-x3.html', $b2b_path, $req_ulid);
		file_put_contents($file, $html);

		$post = _get_form_data($html);
		if ('3' !== $post['details[page_num]']) {
			$msg = [ "Incorect Page Num; Expect:3; Actual:{$post['details[page_num]']};" ];
			$msg[] = _extract_error($html);
			__exit_json([
				'data' => null,
				'meta' => [ 'detail' => implode(' ', $msg) ]
			], 500);
		}

		// Depart
		$post['submitted[departure_date_time][month]'] = $dt0->format('n');
		$post['submitted[departure_date_time][day]'] = $dt0->format('j');
		$post['submitted[departure_date_time][year]'] = $dt0->format('Y');
		$post['submitted[deptime][hour]'] = $dt0->format('g');
		$post['submitted[deptime][minute]'] = intval($dt0->format('i'));
		$post['submitted[deptime][ampm]'] = $dt0->format('a');

		// Arrive
		$post['submitted[arrival_date][month]'] = $dt1->format('n');
		$post['submitted[arrival_date][day]'] = $dt1->format('j');
		$post['submitted[arrival_date][year]'] = $dt1->format('Y');
		$post['submitted[arrtime][hour]'] = $dt1->format('g');
		$post['submitted[arrtime][minute]'] = intval($dt1->format('i'));
		$post['submitted[arrtime][ampm]'] = $dt1->format('a');

		// Vehicle
		switch ($B2B['shipping']['type']) {
			case 'outgoing/delivery':
				// If Delivery (can peek at a page_1 attribute)
				$post['submitted[driver_name]'] = $B2B['shipping']['contact'][0]['name'];
				$post['submitted[vehicle_plate_number]'] = $B2B['shipping']['vehicle']['tag'] ?? 'TBD';
				$post['submitted[vehicle_make]'] = $B2B['shipping']['vehicle']['make'] ?? 'TBD';
				$post['submitted[vehicle_model]'] = $B2B['shipping']['vehicle']['model'] ?? 'TBD';
				$post['submitted[vehicle_color]'] = $B2B['shipping']['vehicle']['color'] ?? 'TBD';
				break;
			case 'outgoing/pickup':
				$post['submitted[driver_name]'] = $B2B['shipping']['contact'][0]['name'];
				$post['submitted[vehicle_plate_number]'] = $B2B['shipping']['vehicle']['tag'] ?? 'PICKUP';
				$post['submitted[vehicle_make]'] = $B2B['shipping']['vehicle']['make'] ?? 'PICKUP';
				$post['submitted[vehicle_model]'] = $B2B['shipping']['vehicle']['model'] ?? 'PICKUP';
				$post['submitted[vehicle_color]'] = $B2B['shipping']['vehicle']['color'] ?? 'PICKUP';
				break;
			case 'outgoing/carrier':
				// $post['submitted[driver_name]'] = $B2B['shipping']['carrier']['name'];
				$post['submitted[driver_name]'] = $B2B['shipping']['contact'][0]['name'];
				$post['submitted[vehicle_plate_number]'] = 'Fleet Vehicle';
				$post['submitted[vehicle_make]'] = 'Fleet Vehicle';
				$post['submitted[vehicle_model]'] = 'Fleet Vehicle';
				$post['submitted[vehicle_color]'] = 'Fleet Vehicle';
				break;
		}


		// Submit to NExt Page
		$file = sprintf('%s/%s-x4-post.json', $b2b_path, $req_ulid);
		file_put_contents($file, __json_encode($post));

		$res4 = $gua->post('/manifest', [ 'form_params' => $post]);
		$html = $res4->getBody()->getContents();
		$file = sprintf('%s/%s-x4.html', $b2b_path, $req_ulid);
		file_put_contents($file, $html);
		$post = _get_form_data($html);
		if ('4' !== $post['details[page_num]']) {
			$msg = [ "Incorect Page Num; Expect:4; Actual:{$post['details[page_num]']};" ];
			$msg[] = _extract_error($html);
			__exit_json([
				'data' => null,
				'meta' => [ 'detail' => implode('; ', $msg) ]
			], 500);
		}

		//
		$post['submitted[destination_license_name]'] = $B2B['target']['name'];
		$post['submitted[destination_license_number]'] = $B2B['target']['code'];
		$post['submitted[destination_license_email]'] = $B2B['target']['email'];
		$post['submitted[destination_license_phone]'] = $B2B['target']['phone'];
		$post['submitted[destination_license_address]'] = $B2B['target']['address_full'];
		$post['submitted[items_shipped]'] = $line_item_text;

		$file = sprintf('%s/%s-x5-post.json', $b2b_path, $req_ulid);
		file_put_contents($file, __json_encode($post));

		// Submit to Next Page
		// $html = 'Thank you, your submission has been received.';
		$res5 = $gua->post('/manifest', [ 'form_params' => $post ]);
		$html = $res5->getBody()->getContents();
		$file = sprintf('%s/%s-x5.html', $b2b_path, $req_ulid);
		file_put_contents($file, $html);

		$err = _extract_error($html);
		if ( ! empty($err)) {
			$msg = [ 'Failed on Destination Details' ];
			$msg[] = $err;
			__exit_json([
				'data' => null,
				'meta' => [ 'detail' => implode('; ', $msg) ]
			], 500);
		}

		if (preg_match('/Thank you, your submission has been received./', $html)) {
			__exit_json([
				'data' => [
					'id' => $req_ulid
				],
				'meta' => [ 'detail' => 'Great Success! You should get a confirmation email from the LCB' ]
			]);
		}

		$msg = [];
		$msg[] = 'We cannot tell what happened';
		$msg[] = $err;

		__exit_json([
			'data' => [
				'id' => $req_ulid
			],
			'meta' => [ 'detail' => implode('; ', $msg) ]
		]);

	}

}


/**
 *
 */
function _get_form_data($html)
{

	$dom = new DOMDocument();
	$dom->loadHTML($html);

	$post = [];

	$form = $dom->getElementById('webform-client-form-6316');
	if (empty($form)) {
		return $post;
	}


	$field_list = $form->getElementsByTagName('input');
	foreach ($field_list as $f) {

		$k = $f->getAttribute('name');
		$v = $f->getAttribute('value');

		$post[$k] = $v;

	}

	// Add Selects
	$field_list = $form->getElementsByTagName('select');
	foreach ($field_list as $f) {

		$k = $f->getAttribute('name');
		$v = $f->getAttribute('value');

		$post[$k] = $v;

	}


	return $post;

}

/**
 *
 */
function _extract_error($html)
{
	// <div class="messages error">
	// <h2 class="element-invisible">Error message</h2>
	// UBI Number field is required. [Update here](https://app.openthc.com/settings/company)</div>

	if (preg_match('/<div class="messages error">(.+?)<\/div>/ms', $html, $m)) {
		return trim(strip_tags($m[1]));
	}

}
