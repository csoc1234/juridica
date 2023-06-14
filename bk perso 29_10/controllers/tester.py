    
from PIL import Image, ImageDraw, ImageFont
    
    
img = Image.new('RGB', (400, 400), color = (255, 255, 255))
d = ImageDraw.Draw(img,)
d.text((10,10), str('c0b1c73c9342980c12020df602b9aaa29a66b74370292cd53c2644ed35a0f4ea') , fill=(113,113,113))
img.save('pil_text.jpg')
im = Image.open('pil_text.jpg')
img_hash=im.rotate(90)
img_hash=img_hash.crop((0,0,30,400))
img_hash.save('/var/www/html/personerias/web/img/hash.jpg','JPEG')