ko.extenders.numeric = function (target, precision) {
    var result = ko.computed({
        read: function () {
            if (target() != undefined) {
                var numberSeparators = target().toString().split('.').length - 1;
                // Check that there is a dot separator at most in the input
                if (numberSeparators <= 1) {

                    var tooManyDecimals = false;

                    if (target().toString().split('.').length == 2) {
                        var decimalLength = target().toString().split('.')[1].length;

                        tooManyDecimals = decimalLength > precision;
                    }

                    if (!tooManyDecimals) {
                        var floatValue = parseFloat(target());
                        if (!isNaN(floatValue)) {
                            return floatValue.toFixed(precision);
                        } else {
                            return null;
                        }
                    }
                    else {
                        return target();
                    }
                }
                else {
                    return target();
                }
            } else {
                return null;
            }
        },
        write: target
    });

    result.raw = target;
    return result;
};

// Custom Knockout binding that makes elements shown/hidden via jQuery's fadeIn()/fadeOut() methods
ko.bindingHandlers.fadeVisible = {
    init: function (element, valueAccessor) {
        // Initially set the element to be instantly visible depending on the value
        var value = valueAccessor();
        $(element).toggle(ko.unwrap(value)); // Use "unwrapObservable" so we can handle values that may or may not be observable
    },
    update: function (element, valueAccessor) {
        // Whenever the value subsequently changes, slowly fade the element in or out
        var value = valueAccessor();
        ko.unwrap(value) ? $(element).fadeIn() : $(element).fadeOut();
    }
};