<?php

require_once'Core/init.php';

$user = new User;

if(!$user->isLoggedIn())
{
	Redirect::to('index.php');
}

if(Input::exists())
{
	if(Token::check(Input::get('_token')))
	{
		$json['error_status'] = false;
		$json['_token'] = Token::generate();
		$Validate = new Validate;
		$Validate->check($_POST, array(
			"github_url" => array(
				"min" => 10
				),
			"twitter_url" => array(
				"min" => 10
				),
			"facebook_url" => array(
				"min" => 10
				),
			"description" => array(
				"required" => true,
				"min" => 10
				)
			));
		if($Validate->passed())
		{
			$fields = array();
			$fields['description'] = Input::get('description');		// add description to the array that is gonna be passed for the insert query
			if(!empty(Input::get('github_url')))
			{
				$fields["github_url"] = Input::get('github_url');	// add github_url if user has entered github_url
			}
			if(!empty(Input::get('facebook_url')))
			{
				$fields["facebook_url"] = Input::get('facebook_url');
			}
			if(!empty(Input::get('twitter_url')))
			{
				$fields["twitter_url"] = Input::get("twitter_url");
			}
			if(!empty($_FILES))	// insert the data if no image uploaded
			{
				$target_dir = Config::get('url/upload_dir').'/';	// target directory where images are gonna be stored
				$target_file = $target_dir . basename($_FILES["profile_pic"]["name"]);	// basename of our file
				$target_file_type = getimagesize($_FILES["profile_pic"]["tmp_name"])[2];	// getting file_type (extension too)
			 	try
				{
					if(!in_array($target_file_type, array(IMAGETYPE_JPEG, IMAGETYPE_PNG)))
					{
						throw new Exception("Incompatible file extension. Only jpeg or png format files allowed");
					}
					if(!move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $target_file))
					{
						throw new Exception("couldn't process profile updation. Please try again later");
					}
					$fields["image_url"] = basename($_FILES["profile_pic"]["name"])	;	// creating index "image_url" if image uploaded is verified
					updateInfo('users', $user->data()->id, $fields);
				}
				catch(Exception $e)
				{
					$json['error_status'] = true;
					$json['error'] = $e->getMessage();
				}
			}
			else
			{
				updateInfo('users', $user->data()->id, $fields);	// insert the data even if there's no image
			}
		}
		else
		{
			$json['error_status'] = true;
			$json['error'] = $Validate->errors()[0];
		}
		
		echo json_encode($json);
	}
}

function updateInfo($table, $id, $fields)
{
	global $user;	// setting $user as global so that it could be used in functions
	try 
	{
		$user->update($table, $id, $fields);
	}
	catch(Exception $e)
	{
		$json['error_status'] = true;
		$json['error'] = $e->getMessage();
	}
}

?>