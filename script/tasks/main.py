#!/usr/bin/env python
# -*- coding: utf-8 -*-

import sys
from helpers.run import *

args = sys.argv[1:]

if len(args) > 0:
    if args[0] == "test":
        Run.call("php /usr/bin/composer.phar run-script test")
