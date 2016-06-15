#!/usr/bin/env bash

# |---------------------------------------------------------------------------------------------------------------------
# | Vagrant Provision Script
# |---------------------------------------------------------------------------------------------------------------------
# |
# | This is the main script for vagrant for the provision process.
# |

PROVISION_DIR="/vagrant/script/vagrant"
LOGS_DIR="/vagrant/log"
HTTP_TEST_DIR="/vagrant/test/http"

# enter permanent su
sudo su

# update before provisioning
apt-get update -y

# install essentials
apt-get install python-software-properties \
                build-essential \
                git \
                nano \
                curl \
                mc \
                -y

# install gcc 4.9
add-apt-repository ppa:ubuntu-toolchain-r/test -y
apt-get update -y

apt-get install gcc-4.9 -y
apt-get install g++-4.9 -y

update-alternatives --install /usr/bin/gcc gcc /usr/bin/gcc-4.9 20
update-alternatives --install /usr/bin/g++ g++ /usr/bin/g++-4.9 20

update-alternatives --config gcc
update-alternatives --config g++

# install stress to be able to quickly check if the VM can use all of the resources of the host CPU
# usage:
  # for 1 core: stress -c 1
  # for 4 cores: stress -c 4
apt-get install stress -y

# update after essentials (especially python-software-properties)
apt-get update -y

# update git
add-apt-repository ppa:git-core/ppa -y
apt-get update -y
apt-get install git -y

# install & configure nginx
    # create folder for logs
    mkdir "$LOGS_DIR"

    # stop & disable apache
    service apache2 stop
    update-rc.d -f apache2 remove

    # install & start nginx
    apt-get install nginx -y
    service nginx start

    # configure nginx
        # copy configuration files
        yes | cp -rf "$PROVISION_DIR/etc/nginx/" /etc/

        # link /vagrant/test/http to /www (on the guest)
        rm -rf /www
        ln -s "$HTTP_TEST_DIR" /www

        # restart service
        service nginx reload

# update python 2.7.x
add-apt-repository ppa:fkrull/deadsnakes-python2.7 -y
apt-get update -y
apt-get install python2.7 -y

# install PHP 5.6
LC_ALL=C.UTF-8 add-apt-repository ppa:ondrej/php -y
apt-get update -y
apt-get install php5.6 \
                php5.6-mbstring \
                php5.6-xml \
                -y

# install Composer into /usr/bin
# an alias for Composer can be found in the .bashrc in the vagrant provision folder,
# so it can be simply called anywhere without passing to PHP with the full path of composer.phar
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php composer-setup.php --install-dir=/usr/bin
php -r "unlink('composer-setup.php');"

# exit sudo mode
su vagrant

# add custom content to .bashrc
cat "$PROVISION_DIR/home/vagrant/.bashrc" >> /home/vagrant/.bashrc

# compile FFMPEG from source
"$PROVISION_DIR/compile-ffmpeg.sh"

# move compiled executables to /home/vagrant/bin
mkdir /home/vagrant/bin
cd /root/bin
mv ffmpeg ffplay ffprobe ffserver /home/vagrant/bin
