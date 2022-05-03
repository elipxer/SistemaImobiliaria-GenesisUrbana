# Sistema Imobiliaria-GenesisUrbana
Sistema de gerenciamento completo para uma imobiliária do Paraná Gênesis Urbana.

O sistema teve seu desenvolvimento feito em laravel 8, usando o template no front Admin LTE. O sistema tem como objetivo gerenciar completamente as atividades da Imobiliaria Genesis Urbana: Seus clientes, as vendas feitas por ele, as parcelas pagas ou atrasadas, um gerador de boletos automatizado e alguns gerenciamentos administrativos internos da empresa. Além de ações feitas por contatos para mudanças de status em cada contrato.

Escolhi o laravel novamente por seus inúmeros recursos e ao lado de uma biblioteca que integrava o laravel ao Admin Lte, tive poucas preocupações em relação ao front. Mas claro sempre tendo uma certa liberdade para dar um toque a mais, para o sistema ficar completo.

A Versão atual é a que se encontra sendo utilizada no dia que esse projeto foi adicionado no git 25/07/2021.

# Instalação
Primeiramente use o composer update ou composer install para criar a pasta vendor. Após isso importe o arquivo sql imobiliaria.sql no seu banco de dados (Não fiz o uso de migrations por não conhecer ainda). Ao fazer isso, vá no arquivo .env.exemple e mude as configurações para as escolhidas por você. As principais são as citadas abaixo:

# ENV Configurações
### API_URL="url local ou da hospedagem" 
### APP_ENV=local ou developmente 
### DB_DATABASE=nome banco de dados 
### DB_USERNAME=usuario do banco de dados 
### DB_PASSWORD=senha do banco de dados

#### Após preencher o arquivo env.example renomeie o arquivo para .env

Essa api utiliza o storage do laravel para upload de imagens, então caso você use localmente utilize o comando php artisan storage:link para criar o link simbólico na pasta public. Caso esteja usando em hospedagem e não tiver acesso ao promp de comando, use a rota "nomedoseudominio/foo" para criar o link simbólico.

Após essas configurações utilize o php artisan key:generate para criar um nova chave, agora basta usar o php artisan serve para iniciar a aplicação caso esteja local.

