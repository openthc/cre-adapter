# Cannabis Reporting Engine Adapters

API Adapters for BioTrack, LeafData and METRC regulatory compliance engines
These scripts expose a common interface for these different API interfaces.


## Examples

There is a convenience factory method to use with the proper configuration (loaded from `etc/cre.ini`).


```php
$cfg = \OpenTHC\CRE::getEngine('usa/wa');
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

* BioTrack: Hawai'i, Illinois, New Mexico, Puerto Rico, Washington*
* LeafData: Pennsylvania*, Utah* Washington
* METRC: Alaska, California, Colorado, Maine, Massachusetts, Michigan, Montana, Nevada, Oklahoma, Oregon

Other engines will be added, of course :)
Some of the engine specific adapters are very thin layers, they really only exist for consistency.

The services for BioTrack/Washington was for their official interface but is now for their system called UCS.
For LeafData there is not yet an API available in Pennsylvania or Utah but we're keeping a watch on that.


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
