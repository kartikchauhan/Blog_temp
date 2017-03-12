<?php

require_once'Core/init.php';

if(Input::exists())
{
	if(Token::check(Input::get('_token')))
	{
		$json['error_status'] = false;
		$json['_token'] = Token::generate();

		$user = new User;
		
		if($user->isLoggedIn())
		{
			$Validate = new Validate;

			$Validate->check($_POST, array(
				'title' => array(
					'required' => true,
					'min' => 5,
					'max' => 100,
					),
				'description' => array(
					'required' => true,
					'min' => 30,
					'max'=> 200,
					),
				'blog'=> array(
					'required' => true,
					'min' => 500,
					)
				));

			if($Validate->passed())
			{
				$blog = new Blog;
				try
				{
					$blog->update('blogs', Input::get('blog_id'), array(
						'title' => Input::get('title'),
						'description' => Input::get('description'),
						'blog' => Input::get('blog')
						));
					if(!$blog)
						throw new Exception("Unable to update blog. Please try again later");
				}
				catch(Exception $e)
				{
					$json['error_status'] = true;
					$json['error'] = $e->getMessage();
				}
			}
			else
			{
				$json['error_status'] = true;
				$json['error'] = $Validate->errors()[0];
			}
		}
		else
		{
			$json['error_status'] = true;
			$json['error'] = "You need to login to update this blog";
		}
		

		echo json_encode($json);
	}
	else
	{
		Redirect::to('authors_info.php');
	}
}
else
{
	Redirect::to('authors_info.php');
}

?>