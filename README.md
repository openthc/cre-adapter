# Cannabis Reporting Engine Adapters

API Adapters for BioTrack, LeafData and METRC regulatory compliance engines
These scripts expose a common interface for these different API interfaces.


## Examples

```php
$cfg = [ /* CRE Specific */ ];
$cre = new \OpenTHC\CRE\BioTrack\NM($cfg);
$res = $cre->plant()->search();
foreach ($res as $rec) {
	echo $rec['variety']['name'] . "\n";
}

$cre = new \OpenTHC\CRE\METRC\Oregon($cfg);
$res = $cre->lot()->search();
foreach ($res as $rec) {
	echo $rec['id'] . ' ' . $rec['product_id'] . "\n";
}
```


## Supported Cannabis Reporting Engines

* \OpenTHC\CRE\BioTrack
* \OpenTHC\CRE\BioTrack\HI
* \OpenTHC\CRE\BioTrack\IL
* \OpenTHC\CRE\BioTrack\NM
* \OpenTHC\CRE\BioTrack\WAUCS
* \OpenTHC\CRE\LeafData
* \OpenTHC\CRE\LeafData\PA
* \OpenTHC\CRE\LeafData\WA
* \OpenTHC\CRE\METRC
* \OpenTHC\CRE\METRC\Alaska
* \OpenTHC\CRE\METRC\California
* \OpenTHC\CRE\METRC\Colorado
* \OpenTHC\CRE\METRC\Maine
* \OpenTHC\CRE\METRC\Massachusetts
* \OpenTHC\CRE\METRC\Michigan
* \OpenTHC\CRE\METRC\Montana
* \OpenTHC\CRE\METRC\Nevada
* \OpenTHC\CRE\METRC\Oregon

Other engines will be added, of course :)
Some of the engine specific adapters are very thin layers, they really only exist for consistency.

## Connect

```php
$cre = \OpenTHC\CRE::factory($cfg);
$cre->ping();
```


## Reading Objects

A low level, GET and POST/PUT interface exists.


```php
$res = $cre->get('/object?page=0&sort=created_at');
$res = $cre->post('/object', $arg);
$res = $cre->put('/object/id', $arg);
```


## High Level API

It's also possible to interface with the objects at a higher level.


```php
$obj_list = $cre->license()->search($arg);
$obj = $cre->license()->single($oid);
$res = $cre->license()->create($obj);
$res = $cre->license()->update($obj);
```
