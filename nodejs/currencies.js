var getCurrency = require('./savvy').getCurrency;
var getCurrencies = require('./savvy').getCurrencies;
const app = new (require('express').Router)();

app.get('/savvy/currencies', (req, res) => {
  if(req.query.order) {
    var orderId = +req.query.order;
    var fiatTotal = 19.99; //get from order

    var token = req.query.token;

    if(token) {
      getCurrency(token, orderId, true, function (curr) {
        res.json(curr); //return this data to savvy form
      });
    } else {
      var returnCurrs = [];
      getCurrencies(function(currs) {
        var collectCurrencies = function (currCodes) {
          if(currCodes.length === 0) {
            res.json(returnCurrs);
            return;
          }
          getCurrency(currCodes.pop(), orderId, true, function(currency) {
            returnCurrs.push(currency);
            collectCurrencies(currCodes);
          });
        };
        collectCurrencies(Object.keys(currs));
      });
    }
  } else {
    res.json({error: 'send the order number'});
  }
});

module.exports = app;