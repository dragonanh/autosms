<?php

/*
 * Created on Nov 6, 2012 8:07:50 PM
 * Author: [email_name]@viettel.com.vn
 * Copyright 2012 Viettel Telecom. All rights reserved.
 * VIETTEL PROPRIETARY/CONFIDENTIAL. Use is subject to license terms.
 * 
 */
/*
 * Created on Oct 26, 2012 7:41:44 PM
 * Author: [email_name]@viettel.com.vn
 * Copyright 2012 Viettel Telecom. All rights reserved.
 * VIETTEL PROPRIETARY/CONFIDENTIAL. Use is subject to license terms.
 * 
 */

class sfResizeMediumThumbnailImage extends sfValidatedFileViettel {   //put your code here

    public function save($file = null, $fileMode = 0666, $create = true, $dirMode = 0777) {
        $saved = parent::save($file, $fileMode, $create, $dirMode);
        $this->createThumbnailImage("thumbnail",150,150);
        $this->createThumbnailImage("thumbnail440",440,310);
        return $saved;
    }

}

?>
