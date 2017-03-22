var model;

$().ready(function () {

    /**
     * Function to filter out particular JSON values.
     * Especially circular values (voucher.parent)
     * @param key
     * @param value
     * @returns {*}
     */
    function jsonKeyFilter(key, value) {
        if (key == 'parent') {
            return null;
        } else {
            return value;
        }
    }

    var optionModel = function(id,name){
        var self = this;
        self.id = id;
        self.name = name;
    }

    function DetailsViewModel(parent, type) {
        var self = this;

        if (type == 'isChild') {
            self.availableTitles = [
                new optionModel(0, 'Please select'),
                new optionModel('Master', 'Master'),
                new optionModel('Miss', 'Miss'),
            ];
        }
        else {
            self.availableTitles = [
                new optionModel(0, 'Please select'),
                new optionModel('Mr', 'Mr'),
                new optionModel('Mrs', 'Mrs'),
                new optionModel('Miss', 'Miss'),
                new optionModel('Ms', 'Ms'),
                new optionModel('Dr', 'Dr'),
            ];
        }

        var days = [new optionModel(0, 'Please select')];

        for (i = 1; i <= 31; i++) {
            days.push(new optionModel(i, i));
        }

        self.availableDateOfBirthDay = days;

        self.availableDateOfBirthMonth = [
            new optionModel(0, 'Please select'),
            new optionModel(1, 'Jan'),
            new optionModel(2, 'Feb'),
            new optionModel(3, 'Mar'),
            new optionModel(4, 'Apr'),
            new optionModel(5, 'May'),
            new optionModel(6, 'Jun'),
            new optionModel(7, 'Jul'),
            new optionModel(8, 'Aug'),
            new optionModel(9, 'Sep'),
            new optionModel(10, 'Oct'),
            new optionModel(11, 'Nov'),
            new optionModel(12, 'Dec')
        ];


        var years = [new optionModel(0, 'Please select')];
        var currentYear = new Date().getFullYear();

        for (i = currentYear; i >= currentYear - 100; i--) {
            years.push(new optionModel(i, i));
        }

        self.availableDateOfBirthYear = years;


        self.parent = parent;

        self.title = ko.observable('0').extend({notEqual: {params: '0', message: "Title is a required field", onlyIf: function() {
            return detailsShouldValidate(parent, type);
        }}});
        self.forename = ko.observable('').extend({required: {message: "Forename is a required field", onlyIf: function() {
            return detailsShouldValidate(parent, type);
        }}});
        self.lastName = ko.observable('').extend({required: {message: "Last Name is a required field", onlyIf: function() {
            return detailsShouldValidate(parent, type);
        }}});
        self.dateOfBirthDay = ko.observable('0').extend({notEqual: {params: '0', message: "Date of Birth day is a required field", onlyIf: function() {
            return detailsShouldValidate(parent, type);
        }}});
        self.dateOfBirthMonth = ko.observable('0').extend({notEqual: {params: '0', message: "Date of Birth month is a required field", onlyIf: function() {
            return detailsShouldValidate(parent, type);
        }}});
        self.dateOfBirthYear = ko.observable('0').extend({notEqual: {params: '0', message: "Date of Birth year is a required field", onlyIf: function() {
            return detailsShouldValidate(parent, type);
        }}});
    }

    function detailsShouldValidate(parent, type) {
        return type == 'isMain' || (type == 'isJoint' && parent.showSecondMember()) || (parent == 'isChild' && this.showChildren());
    }

    function AddressViewModel(parent) {
        var self = this;

        self.parent = parent;
        self.postcode = ko.observable('').extend({ required: { message: "Postcode is a required field"}});
        self.addressLine1 = ko.observable('').extend({ required: { message: "Address Line 1 is a required field"}});
        self.addressLine2 = ko.observable('');
        self.addressLine3 = ko.observable('');
        self.town = ko.observable('').extend({ required: { message: "Town is a required field"}});
        self.county = ko.observable('').extend({ required: { message: "County is a required field"}});
        self.country = ko.observable('');
    }

    function ContactViewModel(parent) {
        var self = this;

        self.parent = parent;
        self.telephone = ko.observable('').extend({ required: { message: "Telephone is a required field"}});
        self.email = ko.observable('').extend({ required: { message: "Email is a required field"}});
        self.informationHeritage = ko.observable('').extend({ required: { message: "Please, select if you wish to receive more information"}});;
        self.contactByPhone = ko.observable('').extend({ required: { message: "Please, select if you wish to be contacted by telephone"}});;
    }

    function FillUserInfoViewModel() {
        var self = this;

        self.availableMembershipType = [
            new optionModel(0, 'Please select'),
            new optionModel('individual-adult', 'Individual Adult'),
            new optionModel('joint-adult', 'Joint Adult'),
            new optionModel('family-one', 'Family (one adult)'),
            new optionModel('family-two', 'Family (two adults)'),
            new optionModel('individual-senior', 'Individual Senior'),
            new optionModel('joint-senior', 'Joint Senior'),
            new optionModel('adult-senior', 'Adult & Senior'),
            new optionModel('student', 'Student')
        ];

        self.membershipPack = ko.observable('');
        self.membershipType = ko.observable(0);

        self.showSecondMember = ko.observable(false);
        self.showChildren = ko.observable(false);

        self.membershipType.subscribe(function(selectedValue) {
            if (selectedValue == 'joint-adult' || selectedValue == 'family-two'
                || selectedValue == 'joint-senior' || selectedValue == 'adult-senior') {
                self.showSecondMember(true);
            }
            else {
                self.showSecondMember(false);
            }

            if (selectedValue == 'family-one' || selectedValue == 'family-two'){
                self.showChildren(true);
            }
            else {
                self.showChildren(false);
            }
        }, this);

        self.mainUser = new DetailsViewModel(self, 'isMain');
        self.jointUser = new DetailsViewModel(self, 'isJoint');
        self.children = ko.observableArray([new DetailsViewModel(self, 'isChild')]);
        self.address = new AddressViewModel(self);
        self.contact = new ContactViewModel(self);

        self.membershipNumber = ko.observable('').extend({ required: { message: "Membership number is a required field", onlyIf: function() {
            return self.membershipPack() == 'Renewal';
        } }});

        // Add a new child
        self.addChild = function () {
            if (self.children().length <= 5) {
                self.children.push(new DetailsViewModel(self, 'isChild'));
            }
            else {
                return false;
            }
        };

        self.submit = function() {
            if (this.errors().length === 0) {
                // Convert the input to JSON
                var json = JSON.parse(ko.toJSON(self, jsonKeyFilter));

                // Perform ajax request
                $.ajax({
                    url: '/tesco-eh/token/ajaxProcessUserInfo',
                    type: 'POST',
                    dataType: 'json',
                    data: json,
                    success: function (response) {
                        /*for (var i = 0; i < self.vouchers().length; i++) {
                            if (response.vouchers[i].error) {
                                self.vouchers()[i].error(response.vouchers[i].error);
                            } else {
                                self.vouchers()[i].error('');
                                self.vouchers()[i].status(response.vouchers[i].status);
                            }
                        }

                        if (response.canProceed) {
                            self.stage(2);
                        }*/
                        alert('success');
                    },
                    error: function () {
                        // console.log('ACTIVATE ERROR :(');
                        alert('fail');
                    },
                    complete: function () {
                        // Clear processing flag (regardless of success)
                        //self.isProcessing(false);
                    }
                });
            }
            else {
                this.errors.showAllMessages();
            }
        };

        // Remove a child
        self.removeChild = function (child) {
            self.children.remove(child);
            return false;
        };

        // Animation callbacks for the children list
        self.removingChild = function (elem) {
            if (elem.nodeType == 1) {
                $(elem).slideUp('fast');
            }
        };

        self.childAdded = function (elem) {
            if (elem.nodeType == 1) {
                $(elem).hide().slideDown('fast')
            }
        };

        ko.validation.init({
            grouping: {
                errorMessageClass: 'error',
                deep: true,
                live: true,
                observable: true
            }
        }, true);

        this.errors = ko.validation.group(this);
    }

    // Create an instance of the view model (we keep a reference so we can make changes)
    model = new FillUserInfoViewModel();

    // Apply the bindings
    ko.applyBindings(model);

});
