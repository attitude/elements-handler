Element: Handler
================

###### Element: Handler

Passes [Request Element](https://github.com/attitude/elements-request) to [Service Element](https://github.com/attitude/elements-service) by handling it to a specific method.

The *Service method* to be invoked it must:

1. have **public** visibility
2. have name composed of optional Auth prefix, [HTTP verb](http://en.wikipedia.org/wiki/Hypertext_Transfer_Protocol#Status_codes) and 2<sup>nd</sup> (*todo: maybe 3<sup>rd</sup>*) part of URI (ex.: `UserService::GETIndex()`, `UserService::POSTStatuses()`,…)
3. If Auth prefix is provided, request must be authorised.

Otherwise, HTTP Exception 405 is raised.

**Enjoy!**

[@martin_adamko](http://twitter.com/martin_adamko)  
*Say hi on Twitter*
