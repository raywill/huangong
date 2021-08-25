lighttpd -f lighttpd.git.conf
hostname -i
grep server.port lighttpd.git.conf
