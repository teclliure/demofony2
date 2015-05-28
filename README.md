Demofony2
------
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/1ea90778-1408-4747-9e8d-d161205ddadf/big.png)](https://insight.sensiolabs.com/projects/1ea90778-1408-4747-9e8d-d161205ddadf)
[![Build Status](https://travis-ci.org/teclliure/demofony2.svg?branch=devel)](https://travis-ci.org/teclliure/demofony2)


License
------
This bundle is under the MIT license. See the complete license in the file:

    LICENSE
Application designed by [Teclliure][1]. Developed by [Flux][2] & [Marc Morales][3]. Code property of [Ajuntament de Premià de Mar][4]

[1]: http://www.teclliure.net/
[2]: http://www.flux.cat
[3]: mailto:marcmorales83@gmail.com
[4]: http://www.premiademar.cat/


Installation
------

To configure google analytics api follow this instructions:

https://github.com/widop/google-analytics/blob/master/doc/usage.md

Configure this params in parameters.yml
    ga_api_client_id: ....q7thkh1f26i6147ovsumhfkgfph@developer.gserviceaccount.com
    ga_api_profile_id: 'ga:12345678'
    ga_api_private_key_file: '%kernel.root_dir%/Resources/bin/d47b32b48c945c6dcac5ed3c05e68b4637aad140-privatekey.p12'
    
To get the ga_profile_id enter in google analytics report of the account are configuring and get the last number in url after p key.

Example:

https://www.google.com/analytics/web/?hl=ca&pli=1#report/visitors-overview/a41127899w70498117p12345678/

ga_api_profile_id: 'ga:12345678'
