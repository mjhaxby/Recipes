# Recipes

This is a simply PHP application for saving and viewing recipes. It's designed to run on a local server, using a desktop or laptop computer to edit recipes, then viewing them on any screen, ideally a touchscreen in the kitchen powered by a Raspberry Pi or similar, but a tablet would also work. 

Recipes is able to read ingredients lists simply by pasting them in and will (as best as it can) divide up the items on the list into their quanities, units and the ingredient itself. This means that in viewing mode, it's possible to adjust the number of desired portions on the fly and the quanities are automatically updated (note: only in the ingredients list itself - so it is best to avoid referring to the quanities in the intructions, using fractions if necessary, e.g. if the the ingredients list 1kg flour, instead of writing "500g of flour" in the instructions, simply write "half of the flour"). Recipes know about most common units used in cooking, both metric and imperial. You can add alternative units in the editor.

Pictograms are used to show the number of portions and the time to prepare and cook. The icons for preparation and cooking have multiple options which can be changed by clicking on them in the editor. Recipes will automatically work out the total time required.

Recipes are saved in folders directly on the service in XML format. Recipes currently supports one level of subfolders, so you can arrange your recipes into the categories you prefer.

Disclaimer: I am not a developer, this is a hobby.