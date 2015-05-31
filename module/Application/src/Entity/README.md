# Generate the Entity from DB schema
=====================================

To generate entity from db:

!TO BE IN APPLICATION ROOT!

php index.php orm:convert-mapping --force --from-database --namespace="Application\Entity\\" annotation module/Application/src/
php index.php orm:generate-entities --generate-methods="Application\Entity\Event" module/Application/src/


mv module/Application/src/Application/Entity/*.* module/Application/src/Entity/
rm -r module/Application/src/Application