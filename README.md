WorldEditArt - ![WorldEditArt](plugin_icon.png)
===

License
===
This software is licensed with GNU Lesser General Public License version 3.


License: 

```
WorldEditArt

Copyright (C) 2015 PEMapModder

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU Lesser General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.
```

You can obtain a full copy of the license at [LICENSE.txt](LICENSE.txt).

Admin guide
===
> Installation, permission management, etc.

Player guide
===
> Concept introduction, commands documentation and user tips

## Introduction
> NOTE: This guide is written assuming that administrators use the default configuration of WorldEditArt.

WorldEditArt is an innovative plugin. This plugin is not written to _"port"_ the MCPC WorldEdit plugin. This plugin is an entirely original work, except that some commands may follow the tradition of WorldEdit to make it easier for understanding. However, a lot of things are different, so don't rely on your knowledge on WorldEdit.

## Concepts
WorldEditArt introduces a number of new concepts into territory of world editing, as well as introduces a lot of improvements to make world editing easier to understand.

#### Sudo mode
World editing plugins are known as one of the most dangerous plugins - I can't imagine anything more dangerous than world editing plugins, except plugins that let players evaluate code/shell commands directly (BAD BAD BAD IDEA!!!). It can, even if not intended, have devastating destruction on the terrain. A simple example is that if you accidentally create a cuboid that spans over a large area of the map, you would probably delete everything that had been built on that world, and you may just be unable to undo that (although WorldEditArt **does** try to let you undo, there are still cases that there is irreversible consequence). Therefore, sudo mode is introduced to prevent users from misusing WorldEditArt accidentally, or "my cat clicked on my phone and all blocks to lava" (I'm sure many people have heard people saying that, most of the time excuses :grin:).

TL;DR: sudo mode lets you use WorldEditArt. When you just joined a server, you cannot use any WorldEditArt commands, except `//sudo` (of course, only if you got that permission e.g. by getting op). When you do `//sudo`, you unlock the WorldEditArt commands for yourself, which will be locked once you leave the server (or if you specify sudo session length - read the commands section for more information).

#### Safe mode and Under-Construction Zones (UCZs)
We may occasionally encounter these cases:

> * This server has a bad builder. He said he was building a stadium, so I went there and watched. Then he suddenly suffocated me with blocks! (The builder tried to set blocks of an area without noticing that a player entered the editing area)
> * I just clicked an extra zero. Why did the selection get so big?

If you have safe mode enabled, WorldEditArt will not change anything for you for blocks that are not in UCZs.

To mark a zone as under-construction:
1. Make a [selection](#selections).
1. Run the command `//uc`.

To unmark a zone as under-construction:
1. Walk into a UCZ.
1. Run the command `//uuc`.


Developer guide
===
> API documentation

Contact
===
### IRC
Server: chat.freenode.net:6667
Private message: PEMapModder

### Gitter
[![Join the chat at https://gitter.im/PEMapModder/WorldEditArt](https://badges.gitter.im/Join%20Chat.svg)](https://gitter.im/PEMapModder/WorldEditArt?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge)

### GitHub issues
Note: use GitHub issues **only** for issue reporting and enhancement suggestions.
https://github.com/PEMapModder/WorldEditArt/issues/new

### Source code
https://github.com/PEMapModder/WorldEditArt
