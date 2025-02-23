FROM wordpress:6.4-apache

# Install required PHP extensions
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Download and install a default theme (Twenty Twenty-Four)
RUN mkdir -p /usr/src/wordpress/wp-content/themes/ \
    && curl -o /tmp/twentytwentyfour.zip https://downloads.wordpress.org/theme/twentytwentyfour.1.0.zip \
    && unzip /tmp/twentytwentyfour.zip -d /usr/src/wordpress/wp-content/themes/ \
    && rm /tmp/twentytwentyfour.zip

# Copy wp-config-render.php to the container
COPY wp-config-render.php /usr/src/wordpress/wp-config-render.php

# Set up wp-config.php during container startup
RUN mv /usr/src/wordpress/wp-config-render.php /usr/src/wordpress/wp-config.php

# Set permissions
RUN chown -R www-data:www-data /usr/src/wordpress

# Use the default apache configuration from the WordPress image
CMD ["apache2-foreground"]