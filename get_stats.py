
from sys import argv

from stats_main import CgminerAPI

import sys
import StringIO

def main():
	cgminer = CgminerAPI(argv[1])
	summary = "cgminer." + argv[2] + "()"
	results = eval(summary)
	print results
	return results

main()


