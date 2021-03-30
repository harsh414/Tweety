{{--Key points--}}

{{-- /*Event: NewUserRegisteredEvent  ------> new user register hoga toh ye fire hoga--}}
{{------> isse NewUserRegisteredListener attach rahega -----> vo new notification(NewRegistrationNotification) bhejega auth->user ko*/--}}

{{-- /*Event: NewTweetEvent  ------> auth()->user() ne naya tweet kia toh uske following k pass noti jaega--}}
{{------> isse NewTweetListener attach rahega -----> vo new notification(NewTweetNotification) bhejega auth->user ko*/--}}

{{--/*Event: FollowEvent ------> auth()->user() ne naya kisi ko follow kia toh uske following k pass noti jaega--}}
{{------> isse FollowListener attach rahega -----> vo new notification(NewFollowerNotification) bhejega jisko follow kia ko*/--}}



