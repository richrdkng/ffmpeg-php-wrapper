#!/usr/bin/env python
# -*- coding: utf-8 -*-

import sys
from helpers.run import *

args = sys.argv[1:]

if len(args) > 0:
    if args[0] == "test":
        filters = ""

        if len(args) > 2:
            if args[1] == "--filter":
                filters = "--filter {test}".format(test=args[2])

        options = "--bootstrap test/bootstrap.php " \
                  "--colors=always"

        tests = "test/cases/."

        Run.call("php vendor/bin/phpunit {options} {filters} {tests}".format(options=options,
                                                                             filters=filters,
                                                                             tests=tests))
