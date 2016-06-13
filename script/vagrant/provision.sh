#!/usr/bin/env bash

# enter permanent su
sudo su

# update before provisioning
apt-get update -y

# install essentials
apt-get install -y python-software-properties
apt-get install -y build-essential git nano curl mc

# install gcc 4.9
add-apt-repository -y ppa:ubuntu-toolchain-r/test
apt-get update -y

apt-get install -y gcc-4.9
apt-get install -y g++-4.9

update-alternatives --install /usr/bin/gcc gcc /usr/bin/gcc-4.9 20
update-alternatives --install /usr/bin/g++ g++ /usr/bin/g++-4.9 20

update-alternatives --config gcc
update-alternatives --config g++

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
apt-get install -y php5.6 php5.6-mbstring php5.6-xml

# install Composer into /usr/bin
# an alias for Composer can be found in the .bashrc in the vagrant provision folder,
# so it can be simply called anywhere without passing to PHP with the full path of composer.phar
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php composer-setup.php --install-dir=/usr/bin
php -r "unlink('composer-setup.php');"

# exit sudo mode
su vagrant

# add custom content to .bashrc
cat > /home/vagrant/.bashrc <<- EOM

# add alias fo Composer
alias composer="php /usr/bin/composer.phar"

# add alias for running tasks
alias run="/vagrant/script/tasks/main.py"

# add /home/vagrant/bin to paths, where FFMPEG can be found
export PATH="/home/vagrant/bin:$PATH"

# navigate to vagrant folder upon login
cd /vagrant

EOM

# compile FFMPEG from source
/vagrant/script/vagrant/compile-ffmpeg.sh

# move compiled executables to /home/vagrant/bin
mkdir /home/vagrant/bin
cd /root/bin
mv ffmpeg ffplay ffprobe ffserver /home/vagrant/bin
