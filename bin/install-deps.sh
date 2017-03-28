#!/bin/bash -ex

# Install dependencies through Composer
COMPOSER_HOME=${HOME}/cache/composer
composer config -g github-oauth.github.com $GITHUB_ACCESS_TOKEN
composer install --prefer-dist --no-interaction

# install bower and bower deps
npm install -g bower
bower install

# install grunt and grunt deps
npm install -g grunt-cli
npm install
npm rebuild node-sass

# install sass & compass
gem install sass
gem install compass
gem install scss_lint

# get the jquery ui css
wget -P vendor/proteusthemes/wp-customizer-utilities/assets https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.css
