#!/bin/bash
function download
{
    error_log=$(wget $1 -O $2 2>&1 > /dev/null) 
    if [ $? -ne 0 ]; then
        echo "<div><pre>Error: Fail to download <a href='$1' target='_blank'>$1</a>"
        echo ""
        echo "$error_log"
        echo "</pre></div>"
        exit
    fi
}

function call
{
    rm data.viz.tmp
    download "$1" "data.viz.tmp"
    DIR=`md5sum data.viz.tmp |  awk '{ print "result/" substr($1,1,2); }'`
    MD5=`md5sum data.viz.tmp | awk '{ print $1; }'`
    mkdir -p $DIR
    drop_percent=$2
	filter_symbol=$4
	if [ -z "$filter_symbol" ]; then
    	cat data.viz.tmp | ./perfdata2graph.py svg $drop_percent > $DIR/$MD5.svg
	fi
	if [ -n "$filter_symbol" ]; then
    	cat data.viz.tmp | python ./symbol_filter.py "$filter_symbol" | ./perfdata2graph.py svg $drop_percent > $DIR/$MD5.svg
	fi
    sed -i '9i <defs><style type="text/css"><![CDATA[path:hover { fill: none; stroke-width:8; } ]]></style></defs>' $DIR/$MD5.svg
    rm data.viz.tmp
    echo "$DIR/$MD5.svg"
}


function flame
{
    rm -f flame_data.viz.tmp
    download "$1" "flame_data.viz.tmp"
    DIR=`md5sum flame_data.viz.tmp |  awk '{ print "result/" substr($1,1,2); }'`
    MD5=`md5sum flame_data.viz.tmp | awk '{ print $1; }'`
    awk '{sub(/^[A-Za-z0-9_]+/,"Thread"); print $0}' flame_data.viz.tmp | FlameGraph/stackcollapse-perf.pl > out_$MD5
    #cat flame_data.agg.viz | FlameGraph/stackcollapse-perf.pl > out_$MD5
    FlameGraph/flamegraph.pl out_$MD5 > $DIR/flame_$MD5.svg
    rm -f out_$MD5
    rm -f flame_data.viz.tmp
    echo "$DIR/flame_$MD5.svg"
}

function stack 
{
    rm -f stack_data.viz.tmp
    #wget $1 -O stack_data.viz.tmp
    download "$1" "stack_data.viz.tmp"
    DIR=`md5sum stack_data.viz.tmp |  awk '{ print "result/" substr($1,1,2); }'`
    MD5=`md5sum stack_data.viz.tmp | awk '{ print $1; }'`
    sed 's/^Thread.*/\nThread 22314 26772285.352955:\t\t1 cycles:/g' stack_data.viz.tmp | sed 's/^\#[0-9]*\s*0x/\t/g' | sed 's/(.*)//g' | sed 's/<.*>//g' | sed 's/ in / /g' | sed 's/ from .*/ (xx)/g' | FlameGraph/stackcollapse-perf.pl > out_$MD5
    FlameGraph/flamegraph.pl out_$MD5 > $DIR/flame_$MD5.svg
    rm -f out_$MD5
    rm -f stack_data.viz.tmp
    echo "$DIR/flame_$MD5.svg"
}
case "$3" in
    call)
          call "$@"
          ;;
    flame)
          flame "$@"
          ;;
    stack)
          stack "$@"
          ;;
esac
