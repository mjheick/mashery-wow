# Mashery-WOW

Pull data from WOW's API

# Code

```
php -r '
$mashery_key = ""; // Mashery key goes here
include("Mashery_WOW.php");
$c=new Mashery_WOW($mashery_key);
print_r($c->getCharacterFeed("emerald-dream","hawwtkunnk"));
'
```
