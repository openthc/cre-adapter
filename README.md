# Cannabis Reporting Engine Adapters

API Adapters for BioTrack, METRC and other Cannabis Regulatory Engines.
These scripts expose a common interface for these different API interfaces.


## Examples

There is a convenience factory method to use with the proper configuration (loaded from `etc/cre.yaml`).


```php
$cfg = \OpenTHC\CRE::getConfig('usa/wa');
$cre = \OpenTHC\CRE::factory($cfg);
$res = $cre->license()->search();
$res = $cre->crop()->search();
foreach ($res as $rec) {
	echo $rec['variety']['name'] . "\n";
}

$res = $cre->lot()->search();
foreach ($res as $rec) {
	echo $rec['id'] . ' ' . $rec['product_id'] . "\n";
}
```


## Supported Cannabis Reporting Engines

* BioTrack: Hawai'i, Illinois, New Mexico, Puerto Rico
* METRC: Alaska, California, Colorado, Maine, Massachusetts, Michigan, Montana, Nevada, Oklahoma, Oregon, etc

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
