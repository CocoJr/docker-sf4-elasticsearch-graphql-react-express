FROM node:9.11

# UPDATE LOCAL DATETIME
RUN rm /etc/localtime
RUN ln -s /usr/share/zoneinfo/Europe/Paris /etc/localtime

# UPDATE & UPGRADE SERVER
RUN echo "deb http://ftp.fr.debian.org/debian jessie-backports main" >> /etc/apt/sources.list
RUN echo "deb http://ftp.fr.debian.org/debian jessie main non-free" >> /etc/apt/sources.list
RUN apt-get update && apt-get -y upgrade
