

для создания тестовой базы:
symfony console --env=test doctrine:database:create
symfony console --env=test doctrine:schema:create


для jwt
mkdir -p config/jwt
openssl genrsa -out config/jwt/private.pem -aes256 4096
openssl rsa -pubout -in config/jwt/private.pem -out config/jwt/public.pem

JWT_PASSPHRASE
