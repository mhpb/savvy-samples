require 'open-uri'
$savvy_SECRET = "secAPIKEY"
$LOCK_ADDRESS_TIMEOUT = 3600

def get_address(order_id, token = 'ETH')
  token = token.downcase
  callback_url = "http://CHANGEME.com/callback/#{order_id}"
  url = "https://api.savvy.io/v3/#{token}/payment/#{CGI.escape(callback_url)}?token=#{$savvy_SECRET}&lock_address=#{$LOCK_ADDRESS_TIMEOUT}"


  begin
    response = ActiveSupport::JSON.decode(open(url).read)
  rescue *HTTP_ERRORS => e
    {
        error: e,
        address: nil
    }
  else
    if response['success']
      {
          error: nil,
          address: response['data']['address']
      }
    end
  end
end

def get_currencies
  $currencies ||= nil

  if $currencies.nil?
    url = "https://api.savvy.io/v3/currencies?token=#{$savvy_SECRET}"
    response = ActiveSupport::JSON.decode(open(url).read)

    if response['success']
      $currencies = response['data']
    end
  end
  $currencies
end

def get_currency(token, order_id, get_address = false)
  rate = get_rate(token)

  if rate
    fiat_value = 19.99
    coins_value = (fiat_value / rate).round(8)

    token = token.downcase

    currencies = get_currencies
    if currencies[token]
      currency              = currencies[token]
      currency[:coinsValue] = coins_value

      if get_address
        currency[:address] = get_address(order_id, token)
      else
        currency[:currencyUrl] = "currencies/#{order_id}/#{token}"
      end
      currency
    end
  else
    puts "can't get rate for #{token}"
    nil
  end
end

def get_rate(cur_code)
  rates = get_rates
  cur_code = cur_code.downcase

  rates[cur_code] ? rates[cur_code]['mid'] : nil
end

def get_rates
  $rates ||= nil

  if $rates.nil?
    url = 'https://api.savvy.io/v3/exchange/usd/rate'
    response = ActiveSupport::JSON.decode(open(url).read)

    if response['success']
      $rates = response['data']
    end
  end
  $rates
end
