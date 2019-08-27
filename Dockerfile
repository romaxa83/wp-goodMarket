FROM hub.t-me.pp.ua/wi1w/webserver:7-1-opencart

WORKDIR /var/www/html
ADD ./www /var/www/html/
#RUN /etc/init.d/apache2 restart

#ENTRYPOINT ["/usr/sbin/apache2ctl", "-D", "FOREGROUND"]

