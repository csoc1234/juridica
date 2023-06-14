#!/usr/bin/env python   
# -*- coding: utf-8 -*-

from __future__ import unicode_literals, print_function

"""

Performs some tests with pyBarcode. All created barcodes where saved in the
tests subdirectory with a tests.html to watch them.

"""
__docformat__ = 'restructuredtext en'

import codecs
import os
import sys
import unittest

from barcode import get_barcode, get_barcode_class, version
try:
    from barcode.writer import ImageWriter
except ImportError:
    ImageWriter = None  # lint:ok


PATH = "/var/www/html/personerias/web/img/"
TESTPATH = os.path.join(PATH, 'codigo')





IMAGES = ('<h3>As PNG-Image</h3><br>\n'
          '<img src="{filename}" alt="PNG {name}"></p>\n')

NO_PIL = '<h3>Pillow was not found. No PNG-Image created.</h3></p>\n'





def barras(code):
    if not os.path.isdir(TESTPATH):
        try:
            os.mkdir(TESTPATH)
        except OSError as e:
            print('Error al generar la imagen.')
            print('Error:', e)
            sys.exit(1)
    objects = []
    append_img = lambda x, y: objects.append(IMAGES.format(filename=x, name=y))
    code =str(code)
    print(len(code))
    bcode = get_barcode('code128', code)
    if ImageWriter is not None:

        bcodec = get_barcode_class('code128')
        bcode = bcodec(code, writer=ImageWriter())
        opts = dict(font_size=14, text_distance=1)
        if 'code128'.startswith('i'):
            opts['center_text'] = False
        else:
            opts['center_text'] = True
        filename = bcode.save(os.path.join(TESTPATH, 'codigo'),
                                options=opts)
        append_img(os.path.basename(filename), bcode.name)
    else:
        objects.append(NO_PIL)
   
if __name__ == '__main__':
    barras()
   
