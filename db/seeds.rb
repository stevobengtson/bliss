# frozen_string_literal: true

# This file should ensure the existence of records required to run the application in every environment (production,
# development, test). The code here should be idempotent so that it can be executed at any point in every environment.
# The data can then be loaded with the bin/rails db:seed command (or created alongside the database with db:setup).
#
# Example:
#
#   ["Action", "Comedy", "Drama", "Horror"].each do |genre_name|
#     MovieGenre.find_or_create_by!(name: genre_name)
#   end
require 'faker'

# Clean up first
puts 'Destroying existing data...'

puts 'Destroying transactions...'
Transaction.destroy_all
puts 'Destroying accounts...'
Account.destroy_all
puts 'Destroying budgets...'
Budget.destroy_all
puts 'Destroying users...'
User.destroy_all

puts 'Creating seed data...'

puts 'Creating user...'
user = User.new(email: 'steven.bengtson@me.com', name: 'Steven Bengtson')
user.password = 'Pass!234'
user.save!

puts 'Creating budget...'
budget = Budget.new(name: '2024', user:)
budget.save!

puts 'Creating accounts...'
['Cash', 'Primary', 'Visa', 'Savings', 'Credit Line'].each do |account_name|
  account = Account.new(name: account_name, user:, budget:)
  account.save!

  puts "Creating transactions for #{account.name}..."
  (0..500).each do |_i|
    transaction = Transaction.new
    transaction.account = account
    transaction.user = user
    transaction.amount = Faker::Number.within(range: -9999.99..9999.99)
    transaction.memo = Faker::Lorem.sentence
    transaction.cleared = Faker::Boolean.boolean
    transaction.entry_date = Faker::Date.between(from: 1.year.ago, to: Date.today)
    transaction.save!
  end
end
