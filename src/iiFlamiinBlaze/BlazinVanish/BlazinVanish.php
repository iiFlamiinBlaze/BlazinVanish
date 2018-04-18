<?php
/**
 *  ____  _            _______ _          _____
 * |  _ \| |          |__   __| |        |  __ \
 * | |_) | | __ _ _______| |  | |__   ___| |  | | _____   __
 * |  _ <| |/ _` |_  / _ \ |  | '_ \ / _ \ |  | |/ _ \ \ / /
 * | |_) | | (_| |/ /  __/ |  | | | |  __/ |__| |  __/\ V /
 * |____/|_|\__,_/___\___|_|  |_| |_|\___|_____/ \___| \_/
 *
 * Copyright (C) 2018 iiFlamiinBlaze
 *
 * iiFlamiinBlaze's plugins are licensed under MIT license!
 * Made by iiFlamiinBlaze for the PocketMine-MP Community!
 *
 * @author iiFlamiinBlaze
 * Twitter: https://twitter.com/iiFlamiinBlaze
 * GitHub: https://github.com/iiFlamiinBlaze
 * Discord: https://discord.gg/znEsFsG
 */
declare(strict_types=1);

namespace iiFlamiinBlaze\AdvancedVanish;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\entity\Effect;
use pocketmine\entity\EffectInstance;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;

class BlazinVanish extends PluginBase{

    const PREFIX = "§6BlazinVanish§b > ";
    const VERSION = "v1.0.1";

    public function onEnable() : void{
        @mkdir($this->getDataFolder());
        $this->saveDefaultConfig();
        $this->getLogger()->info("BlazinVanish " . self::VERSION . " by iiFlamiinBlaze has been enabled");
    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args) : bool{
        if($command->getName() === "vanish"){
            if(!$sender instanceof Player){
                $sender->sendMessage(self::PREFIX . TextFormat::RED . "Use this command in-game");
                return false;
            }
            if(!$sender->hasPermission("vanish.command")){
                $sender->sendMessage(self::PREFIX . TextFormat::RED . "You do not have permission to use this command");
                return false;
            }
            if(empty($args[0])){
                if(!$sender->hasEffect(Effect::INVISIBILITY)){
                    $sender->addEffect(new EffectInstance(Effect::getEffect(Effect::INVISIBILITY), 99999, 0, false));
                    $sender->sendMessage($this->getConfig()->get("vanished-message"));
                }else{
                    $sender->removeEffect(Effect::INVISIBILITY);
                    $sender->sendMessage($this->getConfig()->get("unvanished-message"));
                }
                return false;
            }
            if($this->getServer()->getPlayer($args[0])){
                $player = $this->getServer()->getPlayer($args[0]);
                if(!$player->hasEffect(Effect::INVISIBILITY)){
                    $player->addEffect(new EffectInstance(Effect::getEffect(Effect::INVISIBILITY), 99999, 0, false));
                    $player->sendMessage($this->getConfig()->get("vanished-message"));
                    $sender->sendMessage(self::PREFIX . TextFormat::GREEN . "You have vanished " . TextFormat::AQUA . $player->getName());
                }else{
                    $player->removeEffect(Effect::INVISIBILITY);
                    $player->sendMessage($this->getConfig()->get("unvanished-message"));
                    $sender->sendMessage(self::PREFIX . TextFormat::GREEN . "You have un-vanished " . TextFormat::AQUA . $player->getName());
                }
            }else{
                $sender->sendMessage(self::PREFIX . TextFormat::RED . "Player not found");
                return false;
            }
        }
        return true;
    }
}