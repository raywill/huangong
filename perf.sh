rm data.viz
wget $1 -O data.viz
MD5=`md5sum data.viz | awk '{ print $1; }'`
drop_percent=$2
cat data.viz | ./perfdata2graph.py svg $drop_percent > result/$MD5.svg
rm data.viz
echo "result/$MD5.svg"

