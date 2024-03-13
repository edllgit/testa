Web service readme


#Use

/labAdmin/services/orderfeed.php?feed=feed_name&format=xml|csv&header=true|false&key=passcode&masterkey=

Feeds: conant,conant_ifc,conant_ifc_ca,conant_ait,dr,easyfit,precision,sct,somo,vot,somo_stock

#Files

* data_functions.inc.php - General purpose array and serialization methods
* schemas.inc.php - Definitions of field sets to filter and sort fields by, and to generate headers from.
* export_filters.inc.php - Functions that modify or replace rows from the initial query.
* feeds.inc.php - Feed definitions (combines key, title, schema, filter, and initial queries)
* callable_feed.inc.php - Permits generation of XML/CSV files as strings, for use in e-mail.
* orderfeed.php - The web service interface.

#Design

Instead of duplicating all xml/csv/serialization code and interfaces, we have a single point of entry (orderfeed.php).

This gives us:

* consistency - only one interface to learn
* maintainability - only one copy to modify or change

Data is provided by an initial query and a filter function. 

The filter function may make additional queries, and will rename data to a set of standardized field names.

The schema array determines which fields will be transmitted, and thier order. It also defines CSV headers, as well.


Many exports use the same logic, but just need a different inital query or schema. This design means we don't have to maintain multiple copies of everything.

As you maintain this system, please do not copy and paste more than 2 lines - instead, create a function that exposes the behavior in a generic way.



