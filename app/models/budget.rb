# frozen_string_literal: true

class Budget < ApplicationRecord
  belongs_to :user
  has_many :accounts, dependent: :destroy

  self.implicit_order_column = 'name'
end
