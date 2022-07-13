from PIL import Image

# 90-3998
for i in range(90, 3999):
    src =  "../apeti/200/%d.png" % i
    dst =  "../apeti/100/%d.png" % i
    print(src, '->', dst)
    im = Image.open(src)
    im2 = im.resize((1233, 68))
    im2.save(dst)
