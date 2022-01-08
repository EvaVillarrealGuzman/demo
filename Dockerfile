FROM yiisoftware/yii-php:8.0-apache

RUN a2enmod rewrite
RUN a2enmod ssl

RUN a2ensite default-ssl

EXPOSE 80
EXPOSE 443
