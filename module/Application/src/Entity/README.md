HELP
=====

To generate entity from db:

!TO BE IN APPLICATION ROOT!

$php index.php orm:convert-mapping --force --from-database --namespace="Application\Entity\\" annotation ./data/
$mv data/Application/Entity/*.* module/Application/src/Entity/
$rm -r data/Application
$php index.php orm:generate-entities --regenerate-entities="Application\Entity\<Entity>" module/Application/src/Entity
$mv module/Application/src/Entity/Application/Entity/*.* module/Application/src/Entity/
$rm -r module/Application/src/Entity/Application

Check Entity && update namespace to: namespace Application\Entity;