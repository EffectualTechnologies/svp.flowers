<?php
	define('WP_USE_THEMES', false);
	require( '../../../wp-blog-header.php' );

	if ($webdados_fb_open_graph_settings=webdados_fb_open_graph_load_settings()) {

		if ( isset($_GET['img']) && trim($_GET['img'])!='' ) {
			if ($url=parse_url(trim($_GET['img']))) {
				if ( $url['host']==$_SERVER['HTTP_HOST'] ) {
		
					if( $image=imagecreatefromfile($_SERVER['DOCUMENT_ROOT'].$url['path']) ) {
	
						$thumb_width = 1200;
						$thumb_height = 630;
						
						$width = imagesx($image);
						$height = imagesy($image);
						
						$original_aspect = $width / $height;
						$thumb_aspect = $thumb_width / $thumb_height;
						
						if ( $original_aspect >= $thumb_aspect )
						{
						   // If image is wider than thumbnail (in aspect ratio sense)
						   $new_height = $thumb_height;
						   $new_width = $width / ($height / $thumb_height);
						}
						else
						{
						   // If the thumbnail is wider than the image
						   $new_width = $thumb_width;
						   $new_height = $height / ($width / $thumb_width);
						}
						
						$thumb = imagecreatetruecolor( $thumb_width, $thumb_height );
						
						// Resize and crop
						imagecopyresampled($thumb,
						                   $image,
						                   0 - ($new_width - $thumb_width) / 2, // Center the image horizontally
						                   0 - ($new_height - $thumb_height) / 2, // Center the image vertically
						                   0, 0,
						                   $new_width, $new_height,
						                   $width, $height);
						//Barra
						if (trim($webdados_fb_open_graph_settings['fb_image_overlay_image'])!='') {
							$barra_url = parse_url(trim($webdados_fb_open_graph_settings['fb_image_overlay_image']));
							$barra = imagecreatefromfile($_SERVER['DOCUMENT_ROOT'].$barra_url['path']);
							imagecopy($thumb, $barra, 0, 0, 0, 0, 1200, 630);
						}
	
						header('Content-Type: image/jpeg');
						imagejpeg($thumb, NULL, 95);
						imagedestroy($image);
						imagedestroy($thumb);
						imagedestroy($barra);
					}
		
				}
			}
		}
	}



	function imagecreatefromfile( $filename ) {
	    if (!file_exists($filename)) {
	        throw new InvalidArgumentException('File "'.$filename.'" not found.');
	    }
	    switch ( strtolower( pathinfo( $filename, PATHINFO_EXTENSION ))) {
	        case 'jpeg':
	        case 'jpg':
	            return imagecreatefromjpeg($filename);
	        break;
	
	        case 'png':
	            return imagecreatefrompng($filename);
	        break;
	
	        case 'gif':
	            return imagecreatefromgif($filename);
	        break;
	
	        default:
	            throw new InvalidArgumentException('File "'.$filename.'" is not valid jpg, png or gif image.');
	        break;
	    }
	}