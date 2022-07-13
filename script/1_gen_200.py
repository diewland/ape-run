from PIL import Image
import sys

def gen_sprite(token_id):
    # prepare assets
    im_base = Image.open('base/200.png')
    im_char = Image.open('h90/%s.png' % token_id)
    char_w, char_h = im_char.size
    # put char on sprite
    base_x = 1678
    for y in [6, 4, 2, 4, 6, 6]:
        # load fresh block
        im_block = Image.open('base/transparent_block.png') # 88x96
        # char -> block
        cx = 0 if char_w > 88 else int((88-char_w)/2) # laset or common
        im_block.paste(im_char, (cx, y), mask=im_char) # mask for shaper border
        # block -> sprite
        im_base.paste(im_block, (base_x, 0), mask=im_block)
        # update base_x
        base_x += 88
    # save to disk
    dst =  "../apeti/200/%d.png" % token_id
    im_base.save(dst)
    return dst

# 90-3998
for token_id in range(90, 3999):
    dst = gen_sprite(token_id)
    print('generate sprite..', dst)
