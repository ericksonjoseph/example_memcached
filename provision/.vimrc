" -----------------------------------------------------------------------------------------
"                               VUNDLE PLUGIN MANAGER
"
" reference: https://github.com/gmarik/Vundle.vim
" https://www.digitalocean.com/community/tutorials/how-to-use-vundle-to-manage-vim-plugins-on-a-linux-vps
" git clone https://github.com/gmarik/vundle.git ~/.vim/bundle/vundle
" -----------------------------------------------------------------------------------------
set nocompatible              " be iMproved, required
filetype off                  " required

" set the runtime path to include Vundle
set rtp+=~/.vim/bundle/vundle/
" Start Vundle
" alternatively, pass a path where Vundle should install plugins
"call vundle#begin('~/some/path/here')
call vundle#begin()

" This is the Vundle package, which can be found on GitHub.
Plugin 'gmarik/vundle.git' " let Vundle manage Vundle, required

Bundle 'vexxor/phpdoc.vim'
Plugin 'airblade/vim-gitgutter'
Plugin 'scrooloose/nerdtree.git'
Plugin 'easymotion/vim-easymotion.git'
Plugin 'mustache/vim-mode.git' "syntax coloring for templates
Plugin 'elzr/vim-json' "Json syntax coloring and folding
Plugin 'tpope/vim-fugitive.git' "Git wrapper
"Plugin 'bling/vim-airline.git'
"Plugin 'edkolev/promptline.vim.git'
"Plugin 'powerline/powerline.git'
Plugin 'wesQ3/vim-windowswap'
Plugin 'mattn/emmet-vim'

" To get plugins from vim-scripts.org, you can reference the plugin by name
Plugin 'nginx.vim' "syntax highliging for nginx.conf files
Plugin 'Buffergator'
Plugin 'ctrlp.vim'
"Plugin 'JavaScript-syntax' "wierd colors & heavy folding
"Plugin 'pangloss/vim-javascript' "nice plain colors & helps /w folding (! began giving me errors May 2016)
"Plugin 'Enhanced-Javascript-syntax' "nice colorful & folding
Plugin 'fatih/vim-go' "Golang
Plugin 'majutsushi/tagbar'

" Plugin for xDebug
Plugin 'joonty/vdebug.git'

" All of your Plugins must be added before the following line
call vundle#end()            " required
" To ignore plugin indent changes, instead use:
"filetype plugin on
filetype plugin indent on    " required

" Brief help
" :PluginList       - lists configured plugins
" :PluginInstall    - installs plugins; append `!` to update or just :PluginUpdate
" :PluginSearch foo - searches for foo; append `!` to refresh local cache
" :PluginClean      - confirms removal of unused plugins; append `!` to auto-approve removal
"
" see :h vundle for more details or wiki for FAQ
" Put your non-Plugin stuff after this line
" -----------------------------------------------------------------------------------------

" -----------------------------------------------------------------------------------------
"								 CUSTOM DEFAULTS
" Put swp files in tmp dir
set swapfile
set dir=/tmp

"Color
set t_Co=256 "enable 256 colors
syntax on "enable syntax coloring
color molokai "color scheme"

" Show line numbers
set number

" Enable bling/vim-airline.git plugin
"let g:airline#extensions#tabline#enabled = 1

" Always show statusline
set laststatus=2

" Command Mode AutoComplete ex: Hit <shfit>:, start typing, hit tab
set wildmenu
set wildmode=list:longest,full

"                               -- TABS vs SPACES --
" When I hit tab, enter spaces instead
set expandtab

" Number of spaces to expand an actual tab to
set tabstop=4
set softtabstop=4

" Number of spaces to shift when doing a shift > or shift <
set shiftwidth=4

set nowrap

" Tabs Whitespace End of Line
"set list lcs=tab:»·,eol:¬,trail:·
set list lcs=tab:▸\ ,eol:¬
" -----------------------------------------------------------------------------------------

"								 AUTO COMPLETE

" Autocomplete depending on FileTypeex: from insert mode <hold-ctrl>x <hold-ctrl>o
"autocmd FileType php set omnifunc=phpcomplete#CompletePHP
"autocmd FileType javascript set omnifunc=javascriptcomplete#CompleteJS
"autocmd FileType css set omnifunc=csscomplete#CompleteCSS
"autocmd FileType html set omnifunc=htmlcomplete#CompleteTags
"set omnifunc=syntaxcomplete#Complete

" Set custom dictionary ex: from insert mode <hold-ctrl>x <hold-ctrl>k
set dictionary+=./.vim/dictionaries/words

" Autocomplete (<hold-ctrl>n) should pull from multiple sources
set complete+=k "Pull from Dictionaires "this way we dont have to hit <hold-ctrl>x <hold-ctrl>k every time
set complete+=t "Pull from Tag Files

" Autocomplete using TAB
function! Tab_Or_Complete()
    if col('.')>1 && strpart( getline('.'), col('.')-2, 3 ) =~ '^\w'
        return "\<C-X>\<C-O>"
    else
        return "\<Tab>"
    endif
endfunction
:inoremap <Tab> <C-R>=Tab_Or_Complete()<CR>
" -----------------------------------------------------------------------------------------

"--------------------
" Do we still need this?
"set rtp+=$GOPATH/src/github.com/golang/lint/misc/vim
"--------------------
"GOLANG
au BufRead,BufNewFile *.go set filetype=go
"au BufWritePost *.go silent !unset DYLD_INSERT_LIBRARIES && goimports -w %
"au BufWritePost *.go silent !unset DYLD_INSERT_LIBRARIES && gofmt -w %
"au BufWritePost *.go :e
au BufWritePost *.go syntax on
"au BufWritePost *.go SyntasticCheck govet
"autocmd BufWritePost,FileWritePost *.go execute 'Lint' | cwindow
"let g:syntastic_aggregate_errors = 1
"let g:syntastic_go_checkers = ['govet']

"Go build on \b
au Filetype go set makeprg=go\ build
nmap <Leader>b :make<CR>:copen<CR>
nmap ;t :GoTest<CR>

function! s:GoTest()
    cexpr system("go test --silent")
    copen
endfunction
command! GoTest :call s:GoTest()

"TAGBAR
nnoremap ;tb :Tagbar<CR>
let g:tagbar_type_go = {
    \ 'ctagstype' : 'go',
    \ 'kinds'     : [
        \ 'p:package',
        \ 'i:imports:1',
        \ 'c:constants',
        \ 'v:variables',
        \ 't:types',
        \ 'n:interfaces',
        \ 'w:fields',
        \ 'e:embedded',
        \ 'm:methods',
        \ 'r:constructor',
        \ 'f:functions'
    \ ],
    \ 'sro' : '.',
    \ 'kind2scope' : {
        \ 't' : 'ctype',
        \ 'n' : 'ntype'
    \ },
    \ 'scope2kind' : {
        \ 'ctype' : 't',
        \ 'ntype' : 'n'
    \ },
    \ 'ctagsbin'  : 'gotags',
    \ 'ctagsargs' : '-sort -silent'
    \ }

nnoremap tb :TagbarToggle<CR>
nnoremap ;gi :GoImports<CR>
nnoremap ;gf :GoInfo<CR>
nnoremap ;gt :GoTest<CR>


"SHORTCUTS
nnoremap go :cd ~/vm/app/precise/go/src/github.com/<CR>
nnoremap <C-t> :tabnew %<CR>
nnoremap tn :tabnext<CR>
nnoremap tp :tabprevious<CR>
nnoremap <C-b>p :tabprevious<CR>
nnoremap <C-b>n :tabnext<CR>
nnoremap <C-b>x :bdelete<CR>
nnoremap ;fmt :! go fmt %<CR>
nnoremap ;p :set paste<CR>
nnoremap ;np :set nopaste<CR>

map <cr-n> :NERDTreeToggle<cr>

vnoremap e= :EasyAlign=<CR>
nnoremap ,t :CtrlP<CR>
nnoremap ,g :CtrlPTag<CR>
nnoremap ,l :CtrlPLine<CR>
nnoremap ,m :CtrlPMixed<CR>
nnoremap ,b :CtrlPBuffer<CR>
"nnoremap <Space> :CtrlPBuffer<CR>
nnoremap ,cl :CtrlP <F5><CR>
let g:ctrlp_working_path_mode = 'ra'

nnoremap ,f :set tabstop=4 softtabstop=4 shiftwidth=4 noexpandtab<CR>
nnoremap ,f2 :set tabstop=4 softtabstop=4 shiftwidth=4 expandtab<CR>
nnoremap <C-B>k <C-W><Down>
nnoremap <C-B>i <C-W><Up>
nnoremap <C-B>j <C-W><Left>
nnoremap <C-B>l <C-W><Right>
nnoremap <C-B><C-K> <C-W><Down>
nnoremap <C-B><C-I> <C-W><Up>
nnoremap <C-B><C-J> <C-W><Left>
nnoremap <C-B><C-L> <C-W><Right>

map ff  mzgg=G`z<CR>
vnoremap f =

inoremap jk <Esc>l
nnoremap ;; :w<CR>
nnoremap ;w :w<CR>
nnoremap ;sp :split<CR>
nnoremap ;vsp :vsplit<CR>
nnoremap ;;q :bdelete<CR>
nnoremap ;q :q<CR>
nnoremap ;rf :call g:GitRestoreVimTabs()<CR>
nnoremap ;bw :call Wipeout()<CR>
map i <Up>
map j <Left>
map k <Down>
noremap h i
" Zoom in
nnoremap <silent> <C-w>f :ZoomWin<CR>

" PHP doc
noremap <leader>pd :call PhpDoc()<CR>

" GREP
" opens search results in a window w/ links and highlight the matches
command! -nargs=+ Grep execute 'silent grep! -I -r -n --exclude *.{json,pyc} . -e <args>' | copen | execute 'silent /<args>'
" shift-control-* Greps for the word under the cursor
:nmap <leader>g :Grep <c-r>=expand("<cword>")<cr><cr>

"Folding
set foldmethod=indent   "fold based on indent
set foldnestmax=10      "deepest fold is 10 levels
set nofoldenable        "dont fold by default
set foldlevel=10         "whatever you want

"Search is case insensitive
set ic

set runtimepath^=~/.vim/bundle/ctrlp.vim

" EasyMotion
let g:EasyMotion_leader_key = '<Space>'
map <Space>l <Plug>(easymotion-lineforward)
map <Space>k <Plug>(easymotion-j)
map <Space>i <Plug>(easymotion-k)
map <Space>j <Plug>(easymotion-linebackward)

let g:EasyMotion_startofline = 0 " keep cursor colum when JK motion

" Bidirectional & within line 't' motion
omap t <Plug>(easymotion-bd-tl)
" Use uppercase target labels and type as a lower case
let g:EasyMotion_use_upper = 1
 " type `l` and match `l`&`L`
let g:EasyMotion_smartcase = 1
" Smartsign (type `3` and match `3`&`#`)
let g:EasyMotion_use_smartsign_us = 1

" Bi-directional find motion
nmap <Space>a <Plug>(easymotion-s)
nmap <Space>s <Plug>(easymotion-s2)


autocmd CursorMoved * exe printf('match IncSearch /\V\<%s\>/', escape(expand('<cword>'), '/\'))

"Auto Save & Auto Load Folds
autocmd BufWinLeave *.* mkview
autocmd BufWinEnter *.* silent loadview 

au! BufRead,BufNewFile *.json set filetype=json
augroup json_autocmd
  autocmd!
  autocmd FileType json set autoindent
  autocmd FileType json set formatoptions=tcq2l
  autocmd FileType json set textwidth=78 shiftwidth=4
  autocmd FileType json set softtabstop=4 tabstop=8
  autocmd FileType json set expandtab
  autocmd FileType json set foldmethod=syntax
augroup END


"ENABLE RESIZE WITH MOUSE
"Send more characters for redraws
set ttyfast

" Enable mouse use in all modes
set mouse=a

" Set this to the name of your terminal that supports mouse codes.
" Must be one of: xterm, xterm2, netterm, dec, jsbterm, pterm
set ttymouse=xterm2

"Windowswap
let g:windowswap_map_keys = 0 "prevent default bindings
nnoremap <silent> <leader>yw :call WindowSwap#MarkWindowSwap()<CR>
nnoremap <silent> <leader>pw :call WindowSwap#DoWindowSwap()<CR>
nnoremap <silent> <leader>ww :call WindowSwap#EasyWindowSwap()<CR>

"NerdTree
map <C-n> :NERDTreeToggle<CR>

" xDebug
" Maps the VM's files to your local files so you can use xDebug on your loca code
let g:vdebug_options = {
\       "break_on_open": 0,
\       "path_maps" : {
\               "/var/www/blim/current": "/Users/erick/Clients/televisa/repos/portal-php"
\       }
\}

function! Wipeout()
  " list of *all* buffer numbers
  let l:buffers = range(1, bufnr('$'))

  " what tab page are we in?
  let l:currentTab = tabpagenr()
  try
    " go through all tab pages
    let l:tab = 0
    while l:tab < tabpagenr('$')
      let l:tab += 1

      " go through all windows
      let l:win = 0
      while l:win < winnr('$')
        let l:win += 1
        " whatever buffer is in this window in this tab, remove it from
        " l:buffers list
        let l:thisbuf = winbufnr(l:win)
        call remove(l:buffers, index(l:buffers, l:thisbuf))
      endwhile
    endwhile

    " if there are any buffers left, delete them
    if len(l:buffers)
      execute 'bwipeout' join(l:buffers)
    endif
  finally
    " go back to our original tab page
    execute 'tabnext' l:currentTab
  endtry
endfunction



command! -complete=shellcmd -nargs=+ Shell call s:RunShellCommand(<q-args>)
function! s:RunShellCommand(cmdline)
  echo a:cmdline
  let expanded_cmdline = a:cmdline
  for part in split(a:cmdline, ' ')
     if part[0] =~ '\v[%#<]'
        let expanded_part = fnameescape(expand(part))
        let expanded_cmdline = substitute(expanded_cmdline, part, expanded_part, '')
     endif
  endfor
  botright new
  setlocal buftype=nofile bufhidden=wipe nobuflisted noswapfile nowrap
  call setline(1, 'You entered:    ' . a:cmdline)
  call setline(2, 'Expanded Form:  ' .expanded_cmdline)
  call setline(3,substitute(getline(2),'.','=','g'))
  execute '$read !'. expanded_cmdline
  setlocal nomodifiable
  1
endfunction

function! SyncIST()
    Shell cd ~/vm/app/precise/realtime-ng && ./vm/bin/sync_noconfigs walmart walmart
endfun
function! SyncISTC()
    Shell cd ~/vm/app/precise/istc && ./vm/bin/sync
endfun
function! TestReset()
    Shell test_reset
endfun

nnoremap ;s :call SyncIST()<CR>
nnoremap ;c :call SyncISTC()<CR>
nnoremap ;r :call TestReset()<CR>
