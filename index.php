<?php
$perf_file = $_GET['perf_file'];
$drop_percent = 1;

$msg = "";
if (!empty($perf_file)) {

  $drop_percent = empty($_GET['drop_percent']) ? 1 : floatval($_GET['drop_percent']);
  if ($drop_percent < 0 || $drop_percent > 100) $drop_percent = 1;

  $cmd = "sh perf.sh $perf_file $drop_percent";
  $result = shell_exec($cmd);
  $rule = "/result\/(.*)/i";
  preg_match($rule, $result, $m);
  if (count($m) == 2) {
    header('location: result/' . $m[1]);
    exit(0);
  } else {
    $msg = "<p style='padding:20px;border:1px black solid;'>生成 SVG 失败</p>";
  }
}

?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
<title>桓公</title>
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<link rel="stylesheet" href="/res/bootstrap4.min.css" >
<link rel="icon" type="image/png" href="/favicon.png" />
<style>
body{ padding:10px; padding-bottom:50px;}
table {font-family: Consolas,"Courier New",Courier,FreeMono,monospace !important;}
.fixed{ position:fixed; right:20px; bottom:0px; width:200px; height:50px; background-color:#fef8e9; z-index:9999;border-radius:10px;padding:5px;margin: 0 auto;}
.diff {display:none;margin-left:20px;padding:5px;background-color:#fef8e9;color:black;border-radius:5px;position:absolute;}
.graph-table tr {padding:0px;line-height:0.4em;}
.graph-table>tr>td, .graph-table>tr>th {padding:0px !important;line-height:0.4em !important;vertical-align:middle !important;}
.graph-table>tr>.lastline {padding:0px !important;line-height:1.4em !important;vertical-align:middle !important;}
.graph-table {font-size:10px;line-height:0.6em;width:1000px;}
*{margin: 0; padding: 0;}
.b {height: 14px;}
.empty { height: 14px; background: rgba(200,200,200,0.2);}
.bar { margin-left:5%; margin-top: 20px; margin-bottom:100px; }
.help {background-color:#fef8e9;width:100%;border-left:6px solid orange;padding:30px 20px;}
.shortcut {color:gray;text-align:right;}
</style>
</head>
<body>

<div class='help'><h1>桓公(一键生成perf扁鹊图)</h1>

  <p>使用帮助: 使用下面的命令生成 data.viz 文件，并将 data.viz 放到共享目录下。本工具会自动从共享目录 url 拉取文件并将其可视化。</p>
  <p><pre class="p-3 mb-2 bg-dark text-white">
# 注意：-p 后面是进程ID，改成你要 perf 的进程
sudo perf record -e cycles -c 100000000 -p 87741 -g -- sleep 20
sudo perf script -F ip,sym -f > data.viz
</pre></p>
  <p><pre class="p-3 mb-2 bg-secondary text-white">
# (可选步骤)  如果没有共享目录，那就启动一个 http 服务，把下面的路径填到 URL 一栏
echo http://$(hostname -i):39411/data.viz
python -m SimpleHTTPServer 39411
</pre></p>

  <p>常见问题：
<pre>
perf 图里没符号，一般是因为 binary 被 strip 过；
perf 图是空的，一般是因为 url 路径不对，可以试试地址能不能正常打开。
</pre></p>
  <p>详细介绍：<a href='https://yuque.antfin-inc.com/xiaochu.yh/doc/izz3x0' target='_blank'>https://yuque.antfin-inc.com/xiaochu.yh/doc/izz3x0</a></p>
  <p>快速开始：URL 样例复制到下面的输入框里即可体验效果：<kbd>http://s.oceanbase.icu/xiaochu.yh/share/variable_web/perf/sample.viz</kbd></p>
</div>

<div style='margin-top:20px;'>
<form action="index.php" method="get">
  <div class="form-group">

<div class="row g-12">
  <div class="col-11">
      <label for="perf_file">URL</label>
      <input type="text" class="form-control" id="perf_file" name="perf_file" placeholder="例如：http://s.oceanbase.icu/xiaochu.yh/share/variable_web/perf/sample.viz" value="<?php echo $perf_file; ?>" />
  </div>
  <div class="col-1">
      <label for="drop_percent">Drop 比例</label>
      <input type="text" class="form-control" id="drop_percent" name="drop_percent" placeholder="Drop 比例" value="<?php echo $drop_percent; ?>" />
  </div>
</div>

  </div>
  <button onclick="this.innerText='正在生成扁鹊图';return true;" type="submit" class="btn btn-primary">生成扁鹊图</button>
</form>
</div>
<div style='margin-top:20px'>
  <?php echo $msg; ?>
</div>
<p style='text-align:right'><script type="text/javascript">document.write(unescape("%3Cspan id='cnzz_stat_icon_1280136329'%3E%3C/span%3E%3Cscript src='https://v1.cnzz.com/z_stat.php%3Fid%3D1280136329%26show%3Dpic2' type='text/javascript'%3E%3C/script%3E"));</script></p>
</body>
</html>
