# GFB-SocialClient

Bundle for Symfony2

#### Features

- OOP-based data presentation
- It does not reqiure token (can works in all scopes - not only in request scope)

#### Installation

- Add to composer.json next rows
	
	<br/>"repositories": [<br/>
	  ....<br/>
      **{<br/>
        "type": "vcs",<br/>
        "url": "https://github.com/Cherkesov/GFB-SocialClient.git"<br/>
      }** <br/>
	  ....<br/>
    ]
    
    <br/>and
    
    <br/> **"gfb/social-client-bundle": "dev-master",** <br/><br/>
    
- Run **composer update**

#### Usage

In controller/command call services

- **vk_client** (Vkontakte)
- **fb_client** (Facebook)
- **ig_client** (Instagram)