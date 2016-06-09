#!/usr/bin/env bash

# enter permanent su
sudo su

# update before provisioning
apt-get update -y

# install essentials
apt-get install -y python-software-properties
apt-get install -y build-essential git nano curl mc

# install stress to be able to quickly check if the VM can use all of the resources of the host CPU
# usage:
  # for 1 core: stress -c 1
  # for 4 cores: stress -c 4
apt-get install -y stress

# update after essentials (especially python-software-properties)
apt-get update -y

# update git
add-apt-repository -y ppa:git-core/ppa
apt-get update -y
apt-get install git -y

# update python 2.7.x
add-apt-repository -y ppa:fkrull/deadsnakes-python2.7
apt-get update -y
apt-get install python2.7 -y

# install PHP 5.6
LC_ALL=C.UTF-8 add-apt-repository -y ppa:ondrej/php
apt-get update -y
apt-get install -y php5.6 php5.6-mbstring

# install Composer into /usr/bin
# an alias for Composer can be found in the .bashrc in the vagrant provision folder,
# so it can be simply called anywhere without passing to PHP with the full path of composer.phar
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php composer-setup.php --install-dir=/usr/bin
php -r "unlink('composer-setup.php');"

# download FFMPEG, extract it and prepare it for usage
cd /usr/local/bin
curl -o ffmpeg.tar.xz http://johnvansickle.com/ffmpeg/releases/ffmpeg-release-64bit-static.tar.xz
tar xf ffmpeg.tar.xz

	# rename the extracted ffmpeg folder with release information in folder name (e.g.: ffmpeg-3.0.2-64bit-static)
	# to just simply "ffmpeg"
	for folder in ffmpeg-*; do mv "$folder" ffmpeg; done

# remove the FFMPEG archive after it is not needed
rm ffmpeg.tar.xz

# add custom content to .bashrc
cat > /home/vagrant/.bashrc <<- EOM

# add alias fo Composer
alias composer="php /usr/bin/composer.phar"

# add FFMPEG path
export PATH="/usr/local/bin/ffmpeg:$PATH"

# navigate to vagrant folder upon login
cd /vagrant

EOM
