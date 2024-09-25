# Importing libraries
import pygame
import time
import random

snake_speed = 10
blockSize = 30
offset_y = 60
offset_x = 30

# Window size
window_x = 660
window_y = 600
totalBlocks = ((window_x-(offset_x * 2)) * (window_y-offset_y-offset_x)) / blockSize

# Defining colours
black = pygame.Color(0, 0, 0)
faint_green = pygame.Color(0,20,10)
white = pygame.Color(255, 255, 255)
red = pygame.Color(255, 0, 0)
green = pygame.Color(1, 50, 32)
blue = pygame.Color(0, 0, 255)

# Initialising pygame
pygame.init()

# Initialise game window
pygame.display.set_caption('Aaron Connolly Snakes')
game_window = pygame.display.set_mode((window_x, window_y))

# FPS (frames per second) controller
fps = pygame.time.Clock()

# defining snake default position
snake_position = [90, 150]

# defining first 4 blocks of snakes body
snake_body = [[90, 150],
              [60, 150],
              [30, 150]]

# fruit position
def fruitSpawn(body):
    while True:
        x = [random.randrange(2, ((window_x - offset_x) // blockSize)) * blockSize,
             random.randrange(4, ((window_y - offset_y) // blockSize)) * blockSize]
        if x not in body:  # Ensure the fruit does not spawn inside the snake's body
            return x


fruit_position = fruitSpawn(snake_body)
fruit_spawn = True

# setting default snake direction
direction = 'RIGHT'
change_to = direction

# initial score
score = 0

# game_started flag
game_started = False

def show_score(choice, color, font, size):
    # creating font object score_font
    score_font = pygame.font.SysFont(font, size)

    # create the display surface object
    # score_surface
    score_surface = score_font.render('Score : ' + str(score), True, color)

    # create a rectangular object for the
    # text surface object
    score_rect = score_surface.get_rect()

    # displaying text
    game_window.blit(score_surface, score_rect)

# game over function
def game_over(victory):
    my_font = pygame.font.SysFont('times new roman', 50)
    if victory:
        game_over_surface = my_font.render('You Win!!!', True, white)
    else:
        game_over_surface = my_font.render('Your Score is : ' + str(score), True, red)
    game_over_rect = game_over_surface.get_rect()
    game_over_rect.midtop = (window_x / 2, window_y / 4)
    game_window.blit(game_over_surface, game_over_rect)
    pygame.display.flip()
    time.sleep(2)
    pygame.quit()
    quit()

# Main Function
while True:
    # handling key events
    for event in pygame.event.get():
        if event.type == pygame.KEYDOWN:
            if event.key == pygame.K_UP:
                change_to = 'UP'
                game_started = True  # Start game on first key press
            if event.key == pygame.K_DOWN:
                change_to = 'DOWN'
                game_started = True
            if event.key == pygame.K_LEFT:
                change_to = 'LEFT'
                game_started = True
            if event.key == pygame.K_RIGHT:
                change_to = 'RIGHT'
                game_started = True

    if not game_started:
        # Show "Press any key to start" message
        game_window.fill(black)
        start_font = pygame.font.SysFont('times new roman', 40)
        start_surface = start_font.render('Press arrow key to start', True, white)
        start_rect = start_surface.get_rect(center=(window_x // 2, window_y // 2))
        game_window.blit(start_surface, start_rect)
        pygame.display.flip()
        continue  # Skip the rest of the loop until a key is pressed

    # If two keys pressed simultaneously, we don't want snake to move into two directions
    if change_to == 'UP' and direction != 'DOWN':
        direction = 'UP'
    if change_to == 'DOWN' and direction != 'UP':
        direction = 'DOWN'
    if change_to == 'LEFT' and direction != 'RIGHT':
        direction = 'LEFT'
    if change_to == 'RIGHT' and direction != 'LEFT':
        direction = 'RIGHT'

    # Moving the snake
    if direction == 'UP':
        snake_position[1] -= blockSize
    if direction == 'DOWN':
        snake_position[1] += blockSize
    if direction == 'LEFT':
        snake_position[0] -= blockSize
    if direction == 'RIGHT':
        snake_position[0] += blockSize

    # Snake body growing mechanism
    snake_body.insert(0, list(snake_position))
    if snake_position[0] == fruit_position[0] and snake_position[1] == fruit_position[1]:
        score += 1
        fruit_spawn = False
    else:
        snake_body.pop()

    if not fruit_spawn:
        fruit_position = fruitSpawn(snake_body)
    fruit_spawn = True

    # Checkered background
    background = pygame.Surface(game_window.get_size())
    w, h, c1, c2 = background.get_width() - offset_y, background.get_height() - (offset_x * 3), (144, 238, 144), (100, 170, 120)
    tiles = [((x * blockSize + offset_x, y * blockSize + offset_y, blockSize, blockSize), c1 if (x + y) % 2 == 0 else c2)
             for x in range((w + blockSize - 1) // blockSize)
             for y in range((h + blockSize - 1) // blockSize)]
    [pygame.draw.rect(background, color, rect) for rect, color in tiles]
    game_window.blit(background, (0, 0))

    # Draw grid
    for x in range(0, w, blockSize):
        for y in range(0, h, blockSize):
            rect = pygame.Rect(x + offset_x, y + offset_y, blockSize, blockSize)
            pygame.draw.rect(game_window, white, rect, 1)

    for index, pos in enumerate(snake_body):
        if index == 0:  # This is the head
            pygame.draw.rect(game_window, black, pygame.Rect(pos[0], pos[1], blockSize, blockSize))  # Different color for head
        else:  # The rest of the body
            pygame.draw.rect(game_window, green, pygame.Rect(pos[0], pos[1], blockSize, blockSize))

    pygame.draw.rect(game_window, red, pygame.Rect(fruit_position[0], fruit_position[1], blockSize, blockSize))

    # Game Over conditions
    if snake_position[0] < 30 or snake_position[0] > window_x - 30:
        victory = False
        game_over(victory)
    if snake_position[1] < 60 or snake_position[1] > window_y - 30:
        victory = False
        game_over(victory)
    if len(snake_body) == totalBlocks:
        victory = True
        game_over(victory)

    for block in snake_body[1:]:
        if snake_position[0] == block[0] and snake_position[1] == block[1]:
            victory = False
            game_over(victory)

    # Display score
    show_score(1, white, 'times new roman', 40)

    # Refresh game screen
    pygame.display.update()

    # Frame Per Second /Refresh Rate
    fps.tick(snake_speed)
