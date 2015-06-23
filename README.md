# ThinkopenAt.TimeFlies

TYPO3 Flow package implementing an easy to use time logging web app.

I created this app for supporting my own style of doing time logging.
I log almost everything I do during the work day - but on a plain old
piece of paper. I find it somewhat cumbersome to open up any web based
time logging tool for every task you do and maybe even create a ticket
for it.

So I log all my doings on a sheet of paper and every month or when I
think it makes sense I fire up TimeFlies and start manually entering
in every day. A month of tasks can easily get entered within an hour.

In other tools I tried you have to
1. Create a new time entry by clicking on some link
2. Fill in a bunch of fields
3. Save and submit
4. Goto 1.

TimeFlies is different. It is optimized for the task of transfering paper
based time logging into a structured database backed form.

It features hotkeys making entering time logs more easy and quicker.

Another special about it is that it requires you to set the start and end time
of your tasks. This is different from redmine, achieveo, etc. where you just
enter the amount of hours you worked. So if you fear your customer will think
you are mad if you show them that you recently worked on their project from 10 PM
in the evening till 4 o'clock in the morning then you will have to find your
own solution (Maybe just remove this information when creating the report).

So the point is: If you are not used to paper based time logging, or logging the
start and end of your task maybe TimeFlies just isn't the right tool for you. Instead
take a look at:

* Achievo
* Redmine
* Kimai
* https://en.wikipedia.org/wiki/Comparison_of_time-tracking_software

## Installing

Import this package into your TYPO3 Flow installation in the folder
Packages/Application. After executing the necessary steps

    ./flow package:activate ThinkopenAt.TimeFlies

The package will be installed in your TYPO3 Flow instance.

You can start it up by browsing to:

http://yourflowinstance.org/thinkopenat.timeflies/

Time flies does currently have not a leet styled surface but is rather
rudimentary HTML. Which may show as advantage for you if you tend to
integrated it other Flow applications.

There are currently two types of domain models which you can act on:
* Time entry categories
* Time entries

## Using

As already said TimeFlies tries to improve the way paper based time logging
notes can get transfered into a computer to easy the task of creating reports
or just for archival.

### Time entry categories

First you will have to create some categories. You can create them in a
tree like fashion. So if you have three customers and for one of them
you are doing a lot of projects you could create time entry categories like:

* Customer A
* Customer B
* Customer C
  * Project A
  * Project B

A category is rather a very simple object. It just consists of names and a parent.

### Time entries

When you have created some categories you can start to enter timesheets.

Just open up the URL "yourflowsite.org/thinkopenat.timeflies/item/index". There
you will find a list of your previously created categories. You can straight click
on the "Create a new item" link.

You will then see an interface similar to this screenshot:

![Time entry interface of TimeFlies](/Documentation/Images/TimeEntry.jpg?raw=true "Time entry interface")

As you can see you can easily add new rows by using the "Add line" button.

When adding a new line the start time of the new line will automatically be
prefilled with the end time of the previous entry.

Using the buttons "+" and "-" when in a date or time field you can increase/decrease
the date or the time. Dates are increased/decreased day wise. Time is increased/decreased
in steps of a quarter hour (15 minutes).

The categories you created before can be selected in the drop down. They are rendered
there in a tree-like fashion.

The last field is for entering some comments to each entry - like a reference to a tracker entry,
some internal notes, etc.

Finally you have to use the "Create" button to let all your time entries get persisted by the
repository.

You see - easy as it could be.

Of course there are still quite a few of bugs and known issues which I collect in my non-public
mantis bugtracker. If you are interested in colaboration just contact me.

## Some known issues

* There is no hotkey for adding a new line
* It is not possible to have entries spanning across midnight
* Using an alternate "Add line" hotkey it should be possible to keep the comment and category
* When editing a time-field the auto-sanitize feature can become annoying


