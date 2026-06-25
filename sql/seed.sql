-- The Baker Best — Seed Data (placeholder content)
USE baker_best;

INSERT INTO menu_items (name, category, price, description, allergens, image_path, is_featured, sort_order) VALUES
('Classic Sourdough Loaf', 'Breads', 7.50, 'Naturally leavened with a crisp crust and tangy, airy crumb. Baked fresh daily.', 'Gluten', 'images/menu/sourdough-loaf.jpg', 1, 1),
('Whole Wheat Honey Bread', 'Breads', 6.75, 'Hearty whole wheat sweetened with local wildflower honey.', 'Gluten', 'images/menu/whole-wheat.jpg', 1, 2),
('French Baguette', 'Breads', 4.50, 'Traditional Parisian-style baguette with a golden, crackly crust.', 'Gluten', 'images/menu/baguette.jpg', 0, 3),
('Cinnamon Raisin Swirl', 'Breads', 8.00, 'Soft loaf swirled with cinnamon sugar and plump raisins.', 'Gluten, Dairy', 'images/menu/cinnamon-swirl.jpg', 0, 4),
('Butter Croissant', 'Pastries', 3.75, 'Flaky, buttery layers with a delicate golden finish.', 'Gluten, Dairy, Eggs', 'images/menu/croissant.jpg', 1, 1),
('Almond Danish', 'Pastries', 4.25, 'Buttery pastry topped with sliced almonds and sweet glaze.', 'Gluten, Dairy, Nuts', 'images/menu/almond-danish.jpg', 1, 2),
('Blueberry Muffin', 'Pastries', 3.50, 'Bursting with fresh blueberries in a tender, buttery batter.', 'Gluten, Dairy, Eggs', 'images/menu/blueberry-muffin.jpg', 0, 3),
('Pain au Chocolat', 'Pastries', 4.00, 'Layers of laminated dough wrapped around rich dark chocolate.', 'Gluten, Dairy, Eggs', 'images/menu/pain-chocolat.jpg', 0, 4),
('Chocolate Layer Cake', 'Cakes', 42.00, 'Three layers of moist chocolate cake with silky ganache frosting. Serves 10–12.', 'Gluten, Dairy, Eggs', 'images/menu/chocolate-cake.jpg', 1, 1),
('Carrot Cake', 'Cakes', 38.00, 'Spiced carrot cake with cream cheese frosting and walnut garnish.', 'Gluten, Dairy, Eggs, Nuts', 'images/menu/carrot-cake.jpg', 0, 2),
('Lemon Drizzle Cake', 'Cakes', 35.00, 'Light citrus sponge with a tangy lemon glaze. Perfect for afternoon tea.', 'Gluten, Dairy, Eggs', 'images/menu/lemon-cake.jpg', 0, 3),
('Strawberry Shortcake', 'Cakes', 40.00, 'Vanilla sponge, fresh strawberries, and whipped cream. Seasonal favorite.', 'Gluten, Dairy, Eggs', 'images/menu/strawberry-cake.jpg', 1, 4),
('Pumpkin Spice Loaf', 'Seasonal', 9.00, 'Autumn-spiced pumpkin bread with a cream cheese swirl.', 'Gluten, Dairy, Eggs', 'images/menu/pumpkin-loaf.jpg', 1, 1),
('Hot Cross Buns', 'Seasonal', 3.25, 'Spiced fruit buns with a cross of sweet icing. Available at Easter.', 'Gluten, Dairy, Eggs', 'images/menu/hot-cross.jpg', 0, 2),
('Gingerbread Cookies', 'Seasonal', 2.50, 'Decorated gingerbread cookies with royal icing.', 'Gluten, Eggs', 'images/menu/gingerbread.jpg', 0, 3),
('Fresh Brewed Coffee', 'Beverages', 2.75, 'Locally roasted single-origin drip coffee.', '', 'images/menu/coffee.jpg', 0, 1),
('Chai Latte', 'Beverages', 4.50, 'House-made spiced chai with steamed milk.', 'Dairy', 'images/menu/chai.jpg', 0, 2),
('Hot Chocolate', 'Beverages', 3.95, 'Rich Belgian cocoa with steamed milk and whipped cream.', 'Dairy', 'images/menu/hot-chocolate.jpg', 0, 3),
('Iced Matcha Latte', 'Beverages', 5.25, 'Ceremonial-grade matcha over ice with oat milk.', '', 'images/menu/matcha.jpg', 1, 4);

INSERT INTO gallery_images (file_path, caption, category, sort_order) VALUES
('images/gallery/bread-display.jpg', 'Fresh bread display at opening', 'bakery', 1),
('images/gallery/croissant-closeup.jpg', 'Golden butter croissants', 'products', 2),
('images/gallery/cake-slice.jpg', 'Chocolate layer cake slice', 'products', 3),
('images/gallery/baker-kneading.jpg', 'Hand-kneading sourdough', 'bakery', 4),
('images/gallery/pastry-case.jpg', 'Morning pastry case', 'products', 5),
('images/gallery/wedding-cake.jpg', 'Custom wedding cake', 'events', 6),
('images/gallery/farmers-market.jpg', 'Farmers market booth', 'events', 7),
('images/gallery/sourdough-scoring.jpg', 'Scoring sourdough loaves', 'bakery', 8),
('images/gallery/team-photo.jpg', 'The Baker Best team', 'team', 9),
('images/gallery/seasonal-pies.jpg', 'Seasonal fruit pies', 'products', 10),
('images/gallery/coffee-pastry.jpg', 'Coffee and pastry pairing', 'products', 11),
('images/gallery/kids-decorating.jpg', 'Kids decorating cookies event', 'events', 12);
