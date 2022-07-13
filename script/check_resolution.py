from PIL import Image

# 90-3998
for i in range(90, 3999):
    src =  "h90/%d.png" % i
    im = Image.open(src)
    w, h = im.size
    if w > 88:
        print(src, w)
