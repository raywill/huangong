#!/usr/bin/python

import sys

# usage:
# python symbol_filter.py "LogSlidingWindow" < /share/public/yanran.hfs/test.vi > /share/public/yanran.hfs/test_filtered.vi

# `sub' is the symbol to be filtered
sub = sys.argv[1] if len(sys.argv) > 1 else ''

# `chunk' stores one call stack
# suppose call stack is seperated by blank line
chunk = []

# `has_sub' is True if `chunk' contains `sub'
has_sub = False


for l in sys.stdin:
  if l.strip() == '':
    if has_sub:
      for x in chunk:
        sys.stdout.write(x)
      sys.stdout.write('\n')
    has_sub = False
    chunk = []
  else:
    chunk.append(l)
    if l.find(sub) != -1:
      has_sub = True
