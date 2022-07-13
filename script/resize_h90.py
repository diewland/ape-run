from PIL import Image

def resize_h90(im):
    # trim
    im2 = im.crop(im.getbbox())
    # resize height 90
    w, h = im2.size
    nh = 90
    nw = int((nh * w)/h)
    return im2.resize((nw, nh), Image.ANTIALIAS)

# 90-3998
for i in range(90, 3999):
    src =  "og/%d.png" % i
    dst =  "h90/%d.png" % i
    print(src, '->', dst)
    im = Image.open(src)
    im2 = resize_h90(im)
    im2.save(dst)
