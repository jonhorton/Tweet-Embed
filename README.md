##Tweet Embed

I wrote this fieldtype for a project where there would be an occasion to record a tweet.  My fear, based on the sites current traffic, was that if Twitter's API rate limit was ever reach, then it would affect the usability of the site.  Plus it was a use case I didn't want to accept.  We currently still use ExpressionEngine 1.7 but that will soon change, which is why I will eventually include a EE2 version of this fieldtype.  

The fieldtype uses Twitter's API in two ways: First, A generic API call to retrieve the status and since the data returned only includes a small thumbnail of the users avatar, a second call for that information is made.  All this information is stored in your expressionengine database for easy retrieval. 

####Requirements

- ExpressionEngine 1.7.1
- Fieldframe 1.4.5

####Installation

For EE 1.7.1 -> Place the `tweet` folder containing `ft.tweet.php` into the `Fieldtypes` folder within the Extensions folder of your EE installation.
For EE 2 -> Coming Soon

####Usage

If the name of your custom field fieldtype was `{tweet}`

	{tweet}
		{tweet_screen_name}
		{tweet_text}</div>
		{tweet_url}</div>
		<img src="{tweet_profile_image}" />
	{/tweet}
	
All links and @mentions are converted into active tags.


Let me know if you have any suggestions to make this better. 

Thanks!
