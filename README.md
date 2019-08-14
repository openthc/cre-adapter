# Cannabis Reporting Engine Adapters

API Adapters for BioTrackTHC, LeafData and METRC regulatory compliance engines written in PHP
These scripts expose a common interface for these different API interfaces.


## Examples

```php
$cre = new \OpenTHC\CRE\BioTrack\NM();
$cre->auth('u', 'p');
$res = $cre->plant()->search();
foreach ($res as $rec) {
	echo $rec['strain']['name'];
	echo "\n";
}
```


## Supported Cannabis Reporting Engines

* \OpenTHC\CRE\BioTrack
* \OpenTHC\CRE\BioTrack\HI
* \OpenTHC\CRE\BioTrack\IL
* \OpenTHC\CRE\BioTrack\NM
* \OpenTHC\CRE\BioTrack\WA
* \OpenTHC\CRE\LeafData
* \OpenTHC\CRE\LeafData\WA
* \OpenTHC\CRE\METRC
* \OpenTHC\CRE\METRC\AK
* \OpenTHC\CRE\METRC\CA
* \OpenTHC\CRE\METRC\CO
* \OpenTHC\CRE\METRC\NV
* \OpenTHC\CRE\METRC\OR

Other engines will be added, of course :)
Some of the engine specific adapters are very thin layers, they really only exist for consistency.
