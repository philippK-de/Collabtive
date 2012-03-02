<?PHP
#require("hft_image_errors.php"); // here you can include your own language error file

	//this class works with image
	class hft_image {
		var $image_original;
		var $file_original;
		var $image_original_width;
		var $image_original_height;
		var $image_original_type_code;
		var $image_original_type_abbr;
		var $image_original_html_sizes;

		var $image_resized;
		var $file_resized;
		var $image_resized_width;
		var $image_resized_height;
		var $image_resized_type_code;
		var $image_resized_type_abbr;
		var $image_resized_html_sizes;

		//some settings
		var $jpeg_quality;
		var $use_gd2;


		function hft_image($file_original){
			//constructor of the class
			//it takes given file and creates image out of it
			global $ERR;
			$this->clear(); // clear all.

			if(file_exists($file_original))	{
				$this->file_original = $file_original;
				$this->image_original = $this->imagecreatefromfile($file_original);
				if(!$this->image_original){
					$this->error($ERR["IMAGE_NOT_CREATED_FROM_FILE"]." file=$file_original");
					return false;
				}


			} else {
				$this->error($ERR["FILE_DOESNOT_EXSIT"]." file=$file_original");
			}
		}

		function clear() {
			// clear all the class member varaibles

				$this->image_original			=	0;
				$this->file_original			=	"";
				$this->image_original_width		=	0;
				$this->image_original_height	=	0;
				$this->image_original_type_code	=	0;
				$this->image_original_type_abbr	=	"";
				$this->image_original_html_sizes=	"";

				$this->image_resized			=	0;
				$this->file_resized				=	"";
				$this->image_resized_width		=	0;
				$this->image_resized_height		=	0;
				$this->image_resized_type_code	=	-1;
				$this->image_resized_type_abbr	=	"";
				$this->image_resized_html_sizes	=	"";

				$this->set_parameters();

		}

		function set_parameters($jpeg_quality="90", $use_gd2=true) {

			$this->jpeg_quality=$jpeg_quality;
			$this->use_gd2=$use_gd2;
		}

		function error($msg){
			//error messages and debug info:
			// here you can implement your own error handling
			echo("<hr color='red'><font color='red'><b>$msg</b></font><br> file=<b>".__FILE__."</b><hr color='red'>");
		}


		function imagecreatefromfile($img_file){
			global $ERR;
			$img=0;
			$img_sz =  getimagesize( $img_file ); 	## returns array with some properties like dimensions and type;
			####### Now create original image from uploaded file. Be carefull! GIF is often not supported, as far as I remember from GD 1.6
			switch( $img_sz[2] ){
				case 1:
					$img = $this->_imagecheckandcreate("ImageCreateFromGif", $img_file);
					$img_type = "GIF";
				break;
				case 2:
					$img = $this->_imagecheckandcreate("ImageCreateFromJpeg", $img_file);
					$img_type = "JPG";
				break;
				case 3:
					$img = $this->_imagecheckandcreate("ImageCreateFromPng", $img_file);
					$img_type = "PNG";
				break;
				// would be nice if this function will be finally supported
				case 4:
					$img = $this->_imagecheckandcreate("ImageCreateFromSwf", $img_file);
					$img_type = "SWF";
				break;
				default:
					$img = 0;
					$img_type = "UNKNOWN";
					$this->error($ERR["IMG_NOT_SUPPORTED"]." $img_file");
					break;

			}//case

			if($img){
				$this->image_original_width=$img_sz[0];
				$this->image_original_height=$img_sz[1];
				$this->image_original_type_code=$img_sz[2];
				$this->image_original_type_abbr=$img_type;
				$this->image_original_html_sizes=$img_sz[3];

			}else {
				$this->clear();

			}

			return $img;
		}


		function _imagecheckandcreate($function, $img_file) {
			//inner function used from imagecreatefromfile().
			//Checks if the function exists and returns
			//created image or false
			global $ERR;
			if(function_exists($function)) {
				$img = $function($img_file);
			}else{
				$img = false;
				$this->error($ERR["FUNCTION_DOESNOT_EXIST"]." ".$function);
			}

			return $img;

		}

		function resize($desired_width, $desired_height, $mode="0"){
			//this is core function--it resizes created image
			//if any of parameters == "*" then no resizing on this parameter
			//>> mode = "+" then image is resized to cover the region specified by desired_width, _height
			//>> mode = "-" then image is resized to fit into the region specified by desired_width, _height
			// width-to-height ratio is all the time the same
			//>>mode=0 then image will be exactly resized to $desired_width _height.
			//geometrical distortion can occur in this case.
			// say u have picture 400x300 and there is circle on the picture
			//now u resized in mode=0 to 800x300 -- circle shape will be distorted and will look like ellipse.
			//GD2 provides much better quality but is not everywhere installed
			global $ERR;
			if($desired_width == "*" && $desired_height == "*"){
				$this->image_resized = $this->image_original;
				Return true;
			}
			switch($mode) {
				case "-":
				case '+':
					//multipliers
					if($desired_width != "*") $mult_x = $desired_width / $this->image_original_width;
					if($desired_height != "*") $mult_y = $desired_height / $this->image_original_height;
					$ratio = $this->image_original_width / $this->image_original_height;

					if($desired_width == "*"){
						$new_height = $desired_height;
						$new_width = $ratio * $desired_height;
					}elseif($desired_height == "*"){
						$new_height = $desired_width / $ratio;
						$new_width =  $desired_width;

					}else{
						if($mode=="-"){
								if( $this->image_original_height * $mult_x < $desired_height ){
								//image must be smaller than given $desired_ region
								//test which multiplier gives us best result

									//$mult_x does the job
									$new_width = $desired_width;
									$new_height = $this->image_original_height * $mult_x;
								}else{
									//$mult_y does the job
									$new_width = $this->image_original_width * $mult_y;
									$new_height = $desired_height;
								}

						}else{
							//mode == "+"
							// cover the region
							//image must be bigger than given $desired_ region
							//test which multiplier gives us best result
							if( $this->image_original_height * $mult_x > $desired_height ){
								//$mult_x does the job
								$new_width = $desired_width;
								$new_height = $this->image_original_height * $mult_x;
							}else{
								//$mult_y does the job
								$new_width = $this->image_original_width * $mult_y;
								$new_height = $desired_height;
							}

						}
					}
				break;

				case '0':
					//fit the region exactly.
					if($desired_width == "*") $desired_width = $this->image_original_width;
					if($desired_height == "*") $desired_height = $this->image_original_height;
					$new_width = $desired_width;
					$new_height = $desired_height;

				break;
				default:
					$this->error($ERR["UNKNOWN_RESIZE_MODE"]."  $mode");
				break;
			}

			// OK here we have $new_width _height
			//create destination image checking for GD2 functions:
			if( $this->use_gd2 ){
				if( function_exists("imagecreatetruecolor")){

					$this->image_resized = imagecreatetruecolor($new_width, $new_height) or $this->error($ERR["GD2_NOT_CREATED"]);
				}else {
					$this->error($ERR["GD2_UNAVALABLE"]." ImageCreateTruecolor()");
				}
			} else {


				$this->image_resized = imagecreate($new_width, $new_height) or $this->error($ERR["IMG_NOT_CREATED"]);
			}

			//Resize
			if( $this->use_gd2 ){

				if( function_exists("imagecopyresampled")){
					$res = imagecopyresampled($this->image_resized,
											  $this->image_original,
											  0, 0,  //dest coord
											  0, 0,			//source coord
											  $new_width, $new_height, //dest sizes
											  $this->image_original_width, $this->image_original_height // src sizes
											) or $this->error($ERR["GD2_NOT_RESIZED"]);

				}else {
					$this->error($ERR["GD2_UNAVALABLE"]." ImageCopyResampled()");
				}
			} else {

				$res = imagecopyresized($this->image_resized,
										  $this->image_original,
										  0, 0,  //dest coord
										  0, 0,			//source coord
										  $new_width, $new_height, //dest sizes
										  $this->image_original_width, $this->image_original_height // src sizes
										) or $this->error($ERR["IMG_NOT_RESIZED"]);
			}

		}

		function output_original($destination_file, $image_type="JPG") {
            //outputs original image
            //if destination file is empty  image will be output to browser
            // right now $image_type can be JPG or PNG
            return _output_image($destination_file, $image_type, $this->image_original);
        }

		function output_resized($destination_file, $image_type="JPG") {
            //if destination file is empty  image will be output to browser
            // right now $image_type can be JPG or PNG
            $res = $this->_output_image($destination_file, $image_type, $this->image_resized);
            if(trim($destination_file)){
                $sz=getimagesize($destination_file);
                $this->file_resized = $destination_file;
                $this->image_resized_width = $sz[0];
                $this->image_resized_height = $sz[1];
                $this->image_resized_type_code=$sz[2];
                $this->image_resized_html_sizes=$sz[3];
                    //only jpeg and png are really supported, but I'd like to think of future
                switch($this->image_resized_html_sizes){
                    case 0:
                        $this->image_resized_type_abbr = "GIF";
                    break;
                    case 1:
                        $this->image_resized_type_abbr = "JPG";
                    break;
                    case 2:
                        $this->image_resized_type_abbr = "PNG";
                    break;
                    case 3:
                        $this->image_resized_type_abbr = "SWF";
                    break;
                    default:
                        $this->image_resized_type_abbr = "UNKNOWN";
                    break;
                }

            }
            return $res;
        }

        function _output_image($destination_file, $image_type, $image){
            //if destination file is empty  image will be output to browser
            // right now $image_type can be JPEG or PNG
            global $ERR;
            $destination_file = trim($destination_file);
            $res = false;
            if($image){
                switch($image_type) {
                    case 'JPEG':
                    case 'JPG':

                        $res = ImageJpeg($image, $destination_file, $this->jpeg_quality);
                    break;
                    case 'PNG':
                        $res = Imagepng($image, $destination_file);
                    break;
                    default:
                        $this->error($ERR["UNKNOWN_OUTPUT_FORMAT"]." $image_type");
                    break;

                }
            }else{
                $this->error($ERR["NO_IMAGE_FOR_OUTPUT"]);
            }
            if(!$res) $this->error($ERR["UNABLE_TO_OUTPUT"]." $destination_file");
            return $res;
        }
}


?>