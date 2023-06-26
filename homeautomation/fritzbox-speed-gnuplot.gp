#!/usr/local/bin/gnuplot --persist

# call with gnuplot -c ./fritzbox-speed-gnuplot.gp ./fritzbox-speed.csv 

reset
set terminal png size 2000, 800

# Data file uses semikolon as a separator
set datafile separator ';'
 
# Title of the plot
set title "Fritzbox average rates"
 
# We want a grid
set grid
 
# Ignore missing values
#set datafile missing "NaN"
 
# X-axis label
set xdata time
set timefmt "%Y%m%d-%H%M"
 
# Title for Y-axis
set ylabel "KBit/s"

set yrange [-1000:30000]

# generate a legend which is placed underneath the plot
set key outside top right box
 
# output into png file
set terminal png large
set output "fritzbox-speed.png"
  
  
plot "fritzbox-speed-rearranged.csv" using 1:3 with lines title 'download', '' using 1:5 with lines title 'upload'

#plot "$1" index 0 using 2:3 with lines, '' index 1 using 2:3 with lines

#plot for [IDX=0:43] "$1" IDX u 2:3 w lines title columnheader(1)

